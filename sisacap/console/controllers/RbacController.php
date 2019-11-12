<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class RbacController extends Controller{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $userRule = new \common\rbac\UserGroupRule;
        $auth->add($userRule);

        $user = $auth->createRole('user');
        $user->ruleName = $userRule->name;
        $auth->add($user);

        $manager = $auth->createRole('manager');
        $manager->ruleName = $userRule->name;
        $auth->add($manager);
        $auth->addChild($manager, $user);
        
        $instructor = $auth->createRole('instructor');
        $instructor->ruleName = $userRule->name;
        $auth->add($instructor);
        $auth->addChild($instructor, $user);
        
        $submanager = $auth->createRole('submanager');
        $submanager->ruleName = $userRule->name;
        $auth->add($submanager);
        $auth->addChild($submanager, $user);

        $admin = $auth->createRole('administrator');
        $admin->ruleName = $userRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $instructor);
        $auth->addChild($admin, $submanager);
        
        Console::output('Success! RBAC roles has been added.');
    }
} 