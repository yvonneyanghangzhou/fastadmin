<?php

return array(
  'name' => '考试系统',
  'beian' => '',
  'cdnurl' => '',
  'version' => '5.0',
  'timezone' => 'Asia/Shanghai',
  'forbiddenip' => '',
  'languages' =>
                array(
                  'backend' => 'zh-cn',
                  'frontend' => 'zh-cn',
                ),
  'fixedpage' => 'dashboard',
  'categorytype' =>
                array(
                  'default' => 'Default',
                  'page' => 'Page',
                  'article' => 'Article',
                  'test' => 'Test',
                ),
  'configgroup' =>
                array(
                  'basic' => 'Basic',
                  'email' => 'Email',
                  'dictionary' => 'Dictionary',
                  'user' => 'User',
                  'example' => 'Example',
                ),
  'mail_type' => '1',
  'mail_smtp_host' => 'smtp.qq.com',
  'mail_smtp_port' => '465',
  'mail_smtp_user' => '10000',
  'mail_smtp_pass' => 'password',
  'mail_verify_type' => '2',
  'mail_from' => '10000@qq.com',
  //试题类型
  'question_type'=>
                array(
                  'true_false_question'  =>1,
                  'single_choice'        =>2,
                  'multiple_choice'      =>3,
                  'blank_answers'        =>4,
                  'questions_and_answers'=>5,
                ),
);
