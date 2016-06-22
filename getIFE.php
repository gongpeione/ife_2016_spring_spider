<?php
/**
 * Created by PhpStorm.
 * User: geeku
 * Date: 16/6/21
 * Time: 09:20
 */
// 数据库操作类
require_once 'medoo.php';

/**
 * 页面缓存,获取一次之后就保存到本地以供下次使用
 *
 * @param $url 需要获取内容的链接
 * @param $filename 缓存的文件名
 * @return string 返回所获取的内容
 */
function getData($url, $filename) {
    if (file_exists($filename)) {
        return file_get_contents($filename);
    } else {
        //延时 降低获取频率
        sleep(1);
        $content = file_get_contents($url);
        file_put_contents($filename, $content);
        return $content;
    }
}

/**
 * 数据库连接
 * @return medoo
 */
function connectDB() {
    return new medoo([
        'database_type' => 'mysql',
        'database_name' => 'ife_2016_spring',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ]);
}

/**
 * 获取所有任务描述
 *
 * @param int $stage 任务阶段
 */
function getTask($stage = 0) {

    $url = 'http://ife.baidu.com/task/all';
    $base = 'http://ife.baidu.com/';

    // 遍历获取任务内容
    if (file_exists('./taskList')) {
        $content = unserialize(file_get_contents('./taskList'));

        //连接数据库
        $db = connectDB();
        $i = 1;
        echo " - Stage " . $stage . " Start -\n";
        foreach ($content[$stage] as $item) {
            $filename = './tasks/' . explode('?', $item)[1];
            $taskURL = $base . $item;
            $task = getData($taskURL, $filename);

            // 阶段
            $data = ['stage'=> ($stage + 1) . ''];

            // 任务名
            preg_match('/<span class="active">(.*?)<span/', $task, $tmp);
            $data['name'] = $tmp[1];

            // 获取日期,截止日期和难度
            preg_match_all("/<div class=\"task-basic\"><p class=\"\"><span class=\"key\">发布时间：<\/span><span class=\"value\">(.*?)<\/span><\/p><p class=\"\"><span class=\"key\">截止时间：<\/span><span class=\"value\">(.*?)<\/span><\/p><p class=\"\"><span class=\"key\">难度：<\/span><span class=\"value\">(.*?)<\/span>/",  $task, $tmp);
            $data['date'] = @date('Y-m-d H:i:s', strtotime('2016-' . $tmp[1][0]));
            $data['deadline'] = @date('Y-m-d H:i:s', strtotime('2016-' . $tmp[2][0]));
            $data['difficulty'] = $tmp[3][0];

            // 获取描述
            preg_match("/<div class=\"task-detail col-md-9\">([\s\S]+)<h3>已提交任务的团队/", $task, $tmp);
            $data['description'] = $tmp[1];

            $db->insert('ife_task', $data);

            echo '[ ' . $i++ . ' ] ' . $item . ' / ' . ($db->error()[2] ? $db->error()[2] : 'Successful') . "\n";
        }
        echo " - Stage " . $stage . " End -\n\n";

        return;
    }

    // 获取任务链接
    $content = getData($url, './task');

    preg_match_all("/<div class=\"main\">(.*?)<\/main>/", $content, $matches);
    $matches = explode('<ul class="task-list container-fluid">', $matches[1][0]);

    $content = [];
    foreach ($matches as $match) {
        preg_match_all("/<a href=\"(.*?)\" class=\"task-content\">/", $match, $tmp);
        if(count($tmp[1])) {
            $content[] = $tmp[1];
        }
    }
    $content = array_reverse($content);

    print_r($content);

    file_put_contents('./taskList', serialize($content));
}

/**
 * 获取每个任务中排名前十的任务提交
 *
 * @param int $stage 任务阶段
 */
function getWork($stage = 0) {

    $base = 'http://ife.baidu.com/';

    // 遍历获取任务内容
    if (file_exists('./taskList')) {
        $content = unserialize(file_get_contents('./taskList'));

        //连接数据库
        $db = connectDB();

        $i = 1;
        echo " - Stage " . $stage . " Start -\n\n";
        mkdir('./works_' . $stage . '/');
        foreach ($content[$stage] as $item) {
            $filename = './works_' . $stage . '/' . explode('?', $item)[1];
            $taskURL = $base . '/task/getDoneGroupRank?' . explode('?', $item)[1];
            $task = json_decode(getData($taskURL, $filename))->data->groupList;

            $taskID = explode('=', $item)[1];

            echo " - Task " . $taskID . " Start -\n";
            foreach ($task as $taskItem) {

                $data['team'] = $taskItem->groupName;
                $data['score'] = $taskItem->score;
                $data['time'] = $taskItem->submitTime;
                $data['task'] = $taskID;

                // 获取提交的任务内容
                $workContent = getData($base . $taskItem->reviewUrl, $filename . '.' . $taskItem->groupName);

                // 获取用户名
                preg_match("/<a href=\"\/user\/profile\?userId=\d+\">(.*?)<\/a>/", $workContent, $tmp);
                $data['user'] = $tmp[1];

                // 获取review人数
                preg_match("/已经有<em>(\d+)<\/em>人/", $workContent, $tmp);
                $data['review'] = $tmp[1];

                // 获取代码链接
                preg_match("/<p>代码地址：<a href=\"(.*?)\"/", $workContent, $tmp);
                $data['code_url'] = $tmp[1];

                // 获取demo链接
                preg_match("/<p>demo：<a href=\"(.*?)\"/", $workContent, $tmp);
                $data['demo_url'] = $tmp[1];

                // 获取提交的任务描述
                preg_match("/<p class=\"submit-descr\">([\s\S]+)<\/p><\/div><ul/", $workContent, $tmp);
                $data['description'] = $tmp[1];

                $db->insert('ife_work', $data);

                echo '[ ' . $i++ . ' ] Work ' . explode('=', $taskItem->reviewUrl)[1] . ' -> ' . ($db->error()[2] ? $db->error()[2] : 'Successful') . "\n";
            }
            echo " - Task " . $taskID . " End -\n\n";
        }
        echo " - Stage " . $stage . " End -\n\n";

        return;
    }
}


/**
 * 获取每个提交的任务中的Review
 *
 * @param int $stage 任务阶段
 */
function getReview($stage = 0) {
    $base = 'http://ife.baidu.com/';
    // 遍历获取任务内容
    if (file_exists('./taskList')) {
        $content = unserialize(file_get_contents('./taskList'));

        //连接数据库
        $db = connectDB();

        $i = 1;
        echo " - Stage " . $stage . " Start -\n\n";
        foreach ($content[$stage] as $item) {
            $filename = './works_' . $stage . '/' . explode('?', $item)[1];
            $taskURL = $base . '/task/getDoneGroupRank?' . explode('?', $item)[1];
            $task = json_decode(getData($taskURL, $filename))->data->groupList;

            $taskID = explode('=', $item)[1];

            echo " - Task " . $taskID . " Start -\n";
            foreach ($task as $taskItem) {

                $workID = explode('=', $taskItem->reviewUrl)[1];

                // 获取提交的任务内容
                $workContent = getData($base . $taskItem->reviewUrl, $filename . '.' . $taskItem->groupName);

                preg_match("/<ul class=\"review-result-list container-fluid\">([\s\S]+)<div class=\"review-page-wrap\"/", $workContent, $tmp);
                $workContent = explode('<li class="row review-result"', $tmp[1]);
                unset($workContent[0]);

                // Review
                foreach ($workContent as $workItem) {

                    preg_match("/<a href=\"\/group\/profile\?groupId=\d+\" target=\"_blank\">(.*?)<\/a>/", $workItem, $tmp);
                    $data['team'] = $tmp[1];

                    preg_match("/<a href=\"\/user\/profile\?userId=\d+\" target=\"_blank\">(.*?)<\/a>/", $workItem, $tmp);
                    $data['user'] = $tmp[1];

                    preg_match("/<span class=\"score text-success\">(\d+)<\/span>/", $workItem, $tmp);
                    $data['score'] = $tmp[1];

                    preg_match("/<span class=\"time\">(.*?)<\/span>/", $workItem, $tmp);
                    $data['time'] = @date('Y-m-d H:i:s', @strtotime('2016-' . $tmp[1]));

                    preg_match("/<span class=\"comment\">([\s\S]+)<\/span>/", $workItem, $tmp);
                    $data['comment'] = $tmp[1];

                    $data['parent'] = 0;
                    $data['work'] = $workID;

                    print_r($data);

                    $parentID = $db->insert('ife_review', $data);

                    // Review回复
                    preg_match("/<ul class=\"reply-wrap\">([\s\S]+)<\/ul>/", $workItem, $tmp);
                    $workItemReply = explode('<li class="reply"', $tmp[1]);
                    unset($workItemReply[0]);

                    foreach ($workItemReply as $replyItem) {
                        echo $replyItem;
                        preg_match("/<a href=\"\/user\/profile\?userId=\d+\">(.*?)<\/a>/", $replyItem, $tmp);
                        $reply['user'] = $tmp[1];

                        preg_match("/<span class=\"time\">(.*?)<\/span>/", $replyItem, $tmp);
                        $reply['time'] = @date('Y-m-d H:i:s', @strtotime('2016-' . $tmp[1]));

                        preg_match("/<p class=\"reply-content\">([\s\S]+)<\/p>/", $replyItem, $tmp);
                        print_r($tmp);
                        $reply['comment'] = $tmp[1];

                        $reply['parent'] = $parentID;
                        print_r($reply);

                        $db->insert('ife_review', $reply);
                    }
                    echo '[ ' . $i++ . ' ] Work ' . $workID . ' -> ' . ($db->error()[2] ? $db->error()[2] : 'Successful') . "\n";
                }

            }
            echo " - Task " . $taskID . " End -\n\n";
        }
        echo " - Stage " . $stage . " End -\n\n";

        return;
    }
}

// 获取任务描述
//getTask(0);
//getTask(1);
//getTask(2);
//getTask(3);

// 获取任务
//getWork(3);
//getWork(2);
//getWork(1);
//getWork(0);

// 获取Review
//getReview(3);
//getReview(2);
//getReview(1);
//getReview(0);