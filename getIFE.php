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
 * @param $url 需要获取内容的链接
 * @param $filename 缓存的文件名
 * @return string 返回所获取的内容
 */
function getData($url, $filename) {
    if (file_exists($filename)) {
        return file_get_contents($filename);
    } else {
        sleep(1);
        $content = file_get_contents($url);
        file_put_contents($filename, $content);
        return $content;
    }
}

/**
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

            // print_r($task);return;
            echo " - Task " . $taskID . " Start -\n";
            foreach ($task as $taskItem) {

                $data['team'] = $taskItem->groupName;
                $data['score'] = $taskItem->score;
                $data['time'] = $taskItem->submitTime;
                $data['task'] = $taskID;

                $workContent = getData($base . $taskItem->reviewUrl, $filename . '.' . $taskItem->groupName);

                preg_match("/<a href=\"\/user\/profile\?userId=\d+\">(.*?)<\/a>/", $workContent, $tmp);
                $data['user'] = $tmp[1];

                preg_match("/已经有<em>(\d+)<\/em>人/", $workContent, $tmp);
                $data['review'] = $tmp[1];

                preg_match("/<p>代码地址：<a href=\"(.*?)\"/", $workContent, $tmp);
                $data['code_url'] = $tmp[1];

                preg_match("/<p>demo：<a href=\"(.*?)\"/", $workContent, $tmp);
                $data['demo_url'] = $tmp[1];

                preg_match("/<p class=\"submit-descr\">([\s\S]+)<\/p><\/div><ul/", $workContent, $tmp);
                $data['description'] = $tmp[1];

                $db->insert('ife_work', $data);

                echo '[ ' . $i++ . ' ] Work ' . explode('=', $taskItem->reviewUrl)[1] . ' / ' . ($db->error()[2] ? $db->error()[2] : 'Successful') . "\n";
            }
            echo " - Task " . $taskID . " End -\n\n";

//            return;
//
//            // 任务名
//            preg_match('/<span class="active">(.*?)<span/', $task, $tmp);
//            $data['name'] = $tmp[1];
//
//            // 获取日期,截止日期和难度
//            preg_match_all("/<div class=\"task-basic\"><p class=\"\"><span class=\"key\">发布时间：<\/span><span class=\"value\">(.*?)<\/span><\/p><p class=\"\"><span class=\"key\">截止时间：<\/span><span class=\"value\">(.*?)<\/span><\/p><p class=\"\"><span class=\"key\">难度：<\/span><span class=\"value\">(.*?)<\/span>/",  $task, $tmp);
//            $data['date'] = @date('Y-m-d H:i:s', strtotime('2016-' . $tmp[1][0]));
//            $data['deadline'] = @date('Y-m-d H:i:s', strtotime('2016-' . $tmp[2][0]));
//            $data['difficulty'] = $tmp[3][0];
//
//            // 获取描述
//            preg_match("/<div class=\"task-detail col-md-9\">([\s\S]+)<h3>已提交任务的团队/", $task, $tmp);
//            $data['description'] = $tmp[1];
//
//            $db->insert('ife_task', $data);
//
//            echo '[ ' . $i++ . ' ] ' . $item . ' / ' . ($db->error()[2] ? $db->error()[2] : 'Successful') . "\n";
//
//            sleep(1);
        }
        echo " - Stage " . $stage . " End -\n\n";

        return;
    }
}

// 获取任务
//getTask(0);
//getTask(1);
//getTask(2);
//getTask(3);


//getWork(3);
getWork(2);