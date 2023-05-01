<?php

namespace guestbook\Controllers;

class GuestbookController {
     public function execute() {
         $aConfig = require 'config.php';

         $infoMessage = '';

         if (!empty($_POST['name']) && !empty($_POST['email']) &&!empty($_POST['text'])) {

             $aComment = $_POST;
             $aComment['date'] = date('Y-m-d H:i:s');

             $pdo = new \PDO("mysql:dbname={$aConfig['name']};host={$aConfig['host']};charset={$aConfig['charset']}", $aConfig['user']);

             $pdoStatement = $pdo->query("INSERT INTO comments (email, name, text, date) VALUES ('". $aComment['email']."','". $aComment['name']."','". $aComment['text']."','". $aComment['date']."')");


         } elseif (!empty($_POST)) {
             $infoMessage = 'Заполните поля формы!';
         }

         $arguments = [
             'infoMessage' => $infoMessage
         ];

         $this->renderView($arguments);
     }
    public function renderView($arguments = []) {
        ?>


        <!DOCTYPE html>
        <html>

        <?php require_once 'ViewSections/sectionHead.php' ?>

        <body>

        <div class="container">

            <?php require_once 'ViewSections/sectionNavbar.php' ?>
            <br>

            <div class="card card-primary">
                <div class="card-header bg-primary text-light">
                    Guestbook form
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">

                            <!-- form -->
                            <form method="post" name="form" class="fw-bold">
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName">Name</label>
                                    <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputText">Text</label>
                                    <textarea name="text" class="form-control" id="exampleInputText" placeholder="Enter text" required></textarea>
                                </div><br>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </div>
                            </form>
                            <br>
                        </div>

                        <!-- TODO: render php data   -->

                        <?php
                        if ($arguments['infoMessage']) {
                            echo "<span style='color:red'>{$arguments['infoMessage']}</span>";
                        }
                        ?>

                    </div>
                </div>
            </div>

            <br>

            <div class="card card-primary">
                <div class="card-header bg-body-secondary text-dark">
                    Сomments
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">


                            <?php
                            $aConfig = require 'config.php';
                            // select from database
                            $pdo = new \PDO("mysql:dbname={$aConfig['name']};host={$aConfig['host']};charset={$aConfig['charset']}", $aConfig['user']);

                            // render data
                            foreach ($pdo->query('SELECT * FROM comments') as $comment) {
                                print_r($comment['name'] . '<br>');
                                print_r($comment['email'] . '<br>');
                                print_r($comment['text'] . '<br>');
                                print_r($comment['date'] . '<br>');
                                print ('<hr>');
                            }


                            ?>

                        </div>
                    </div>
                </div>
            </div>

        </body>
        </html>
        <?php
    }
}