<?php
function create_cu_boom_me_table(){
    global $wpdb;

    if( get_option('zoom_faculty_table_created')!=true ){
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $zoom_faculty_table = $wpdb->prefix . "zoom_faculty";
        $sql = "CREATE TABLE IF NOT EXISTS $zoom_faculty_table (
              id int(11) NOT NULL AUTO_INCREMENT,
              zoom_accounts_id int(11) NOT NULL,
              faculty_id int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        dbDelta( $sql );

        $zoom_accounts_table = $wpdb->prefix . "zoom_accounts";
        $sql = "CREATE TABLE IF NOT EXISTS $zoom_accounts_table (
              id int(11) NOT NULL AUTO_INCREMENT,
              name varchar(150) NOT NULL,
              email varchar(150) NOT NULL,
              password varchar(150) NOT NULL,
              join_link varchar(200) NOT NULL,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta( $sql );
        update_option('zoom_faculty_table_created',true);
    }
    
}

add_action( 'admin_menu', 'zoom_faculty_course_connect_menu' );
function zoom_faculty_course_connect_menu() {
    add_menu_page('Zoom connect faculty', 'Zoom connect faculty', 'manage_options', 'Zoom-connect-faculty', 'Zoom_connect_faculty_page','dashicons-groups',4);
    add_submenu_page('Zoom-connect-faculty','Zoom Account','Zoom Account','manage_options','zoom-account','zoom_accounts_pae');
}
function zoom_accounts_pae(){
    global $wpdb;
    if(isset($_POST['save_zoom']) && wp_verify_nonce($_POST['_wpnonce'],'save_zoom_user') ){
        foreach ($_POST['zoom'] as $account){
            if( $account['name']!='' && $account['email']!='' && $account['password']!='' ){
                if( isset($account['id']) ){
                    $wpdb->update($wpdb->prefix.'zoom_accounts',array(
                        'name'          =>$account['name'],
                        'email'         =>$account['email'],
                        'password'      =>$account['password'],
                        'join_link'     =>$account['join_link'],
                    ),array(
                        'id'=>$account['id']
                    ));
                }else{
                    $wpdb->insert($wpdb->prefix.'zoom_accounts',array(
                        'name'          =>$account['name'],
                        'email'         =>$account['email'],
                        'password'      =>$account['password'],
                        'join_link'     =>$account['join_link'],
                    ));
                }

            }
        }
    }
    ?>
    <style>
        .save_zoom{
            font-size: 14px;
            background: #0d7c0d;
            border: 0;
            padding: 7px 27px;
            color: white;
            box-shadow: 1px 1px 1px silver;
            border-radius: 2px;
            cursor: pointer;
        }

    </style>
    <div class="wrap">
        <h1 class="wp-heading-inline">Zoom Accounts</h1>
        <hr>
        <button class="add_account page-title-action">Add Account</button>
        <form method="post" autocomplete="off">
            <div class="zoom_accounts">
                <?php
                $zoom_accounts=$wpdb->get_results(' select * from '.$wpdb->prefix.'zoom_accounts ');
                if( !empty($zoom_accounts) ){
                    foreach ($zoom_accounts as $key=>$account){
                        ?>
                        <div class="zoom_account">
                            <table class="form-table">
                                <tr>
                                    <th>Title:</th>
                                    <td><input type="text" name="zoom[<?= $key ?>][name]" value="<?= $account->name ?>"></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><input type="email" autocomplete="new-password" value="<?= $account->email ?>" name="zoom[<?= $key ?>][email]"></td>
                                </tr>
                                <tr>
                                    <th>password:</th>
                                    <td><input type="password" autocomplete="new-password" value="<?= $account->password ?>" name="zoom[<?= $key ?>][password]"><button type="button" class="show_pwd">Show</button></td>
                                </tr>
                                <tr>
                                    <th>Meeting Join Link:</th>
                                    <td>
                                        <input type="hidden" name="zoom[<?= $key ?>][id]" value="<?= $account->id ?>">
                                        <input type="text" value="<?= $account->join_link ?>"  name="zoom[<?= $key ?>][join_link]">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <?php
                    }
                }else{
                    ?>
                    <div class="zoom_account">
                        <table class="form-table">
                            <tr>
                                <th>Title:</th>
                                <td><input type="text" name="zoom[0][name]"></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><input type="email" autocomplete="new-password" name="zoom[0][email]"></td>
                            </tr>
                            <tr>
                                <th>password:</th>
                                <td><input type="password" autocomplete="new-password" name="zoom[0][password]"><button type="button" class="show_pwd">Show</button></td>
                            </tr>
                            <tr>
                                <th>Meeting Join Link:</th>
                                <td><input type="text" name="zoom[0][join_link]"></td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <?php
                }

                ?>

            </div>
            <?= wp_nonce_field('save_zoom_user') ?>
            <button type="submit" class="save_zoom" name="save_zoom">Save</button>
        </form>

    </div>
    <script>
        jQuery(document).ready(function($){
            $(".add_account").click(function () {
                var zoom_key=<?= count($zoom_accounts) ?>;
                $html='<div class="zoom_account"> <table class="form-table"> <tr> <th>Title:</th> <td><input type="text" name="zoom['+zoom_key+'][name]"></td> </tr> <tr> <th>Email:</th> <td><input type="email" name="zoom['+zoom_key+'][email]"></td> </tr> <tr> <th>password:</th> <td><input type="password" name="zoom['+zoom_key+'][password]"><button type="button" class="show_pwd">Show</button></td> </tr> <tr> <th>Meeting Join Link:</th> <td><input type="text" name="zoom['+zoom_key+'][join_link]"></td> </tr> </table> </div>';
                $(".zoom_accounts").append($html);
            });

            $('body').on('click','.show_pwd',function () {
                $(this).parent().find('input').attr('type','text');
                $(this).attr('class','hide_pwd')
            });

            $('body').on('click','.hide_pwd',function () {
                $(this).parent().find('input').attr('type','password');
                $(this).attr('class','show_pwd')
            });
        })

    </script>
    <?php
}


function cu_faculty(){
    global $wpdb;
    $faculties=$wpdb->get_results( "select * from ".$wpdb->prefix."bookme_employee" );
    $data=array();
    foreach ($faculties as $faculty){
        $data[$faculty->id]=$faculty;
    }
    return $data;
}

function zoom_accounts(){
    global $wpdb;
    $accounts=$wpdb->get_results( "select * from ".$wpdb->prefix."zoom_accounts" );
    $data=array();
    foreach ($accounts as $account){
        $data[$account->id]=$account;
    }
    return $data;
}


function zoom_faculty_accounts(){
    global $wpdb;
    $zoom_faculties=$wpdb->get_results( "select * from ".$wpdb->prefix."zoom_faculty" );
    $data=array();
    foreach ($zoom_faculties as $zoom_faculty){
        $data[$zoom_faculty->faculty_id]=$zoom_faculty;
    }
    return $data;
}


function Zoom_connect_faculty_page(){
    date_default_timezone_set(get_option('timezone_string'));
    global $wpdb;
    $message='';

    if( isset($_POST['save_zoom']) && wp_verify_nonce($_POST['_wpnonce'],'connect_zoom_account') ){
        /* check is already zoom account connected to booking */
        if( isset($_POST['zoom_user_id']) ){
            foreach ($_POST['zoom_user_id'] as $faculty_id=>$zoom_id){
                if( $zoom_id!='0' ){
                    $is_exist=$wpdb->get_var( $wpdb->prepare(" select count(id) from ".$wpdb->prefix."zoom_faculty where faculty_id=%s  ",$faculty_id) );
                    if( $is_exist>=1 ){
                        $wpdb->update($wpdb->prefix.'zoom_faculty',
                            array('zoom_accounts_id'=>$zoom_id),
                            array('faculty_id'=>$faculty_id)
                        );
        }else{
                        $wpdb->insert($wpdb->prefix.'zoom_faculty',
                            array(
                                'zoom_accounts_id'  =>$zoom_id,
                                'faculty_id'        =>$faculty_id
                            )
                        );
        }
    }

            }
            $message='<div class="notice notice-success is-dismissible"><p>Zoom Account assigned to faculty</p></div>';
        }
    }

    $faculties=cu_faculty();
    $zoom_accounts=zoom_accounts();
    $zoom_faculty_accounts=zoom_faculty_accounts();

    echo $message;
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Zoom Connect to Faculty</h1>
        <form method="post">
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
            <tr>
                    <th>ID</th>
                    <th>Faculty</th>
                <th>Zoom Account</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $i=1;
                foreach ($faculties as $faculty){
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $faculty->name ?></td>
                        <td>
                            <select name="zoom_user_id[<?= $faculty->id ?>]">
                                <option value="0">Select..</option>
                                    <?php

                                    foreach ($zoom_accounts as $account){
                                        $sel='';
                                    if( isset($zoom_faculty_accounts[$faculty->id]) ){
                                        if( $zoom_faculty_accounts[$faculty->id]->zoom_accounts_id==$account->id ){
                                                $sel='selected';
                                            }
                                        }
                                        echo '<option '.$sel.' value="'.$account->id.'">'.$account->name.'</option>';
                                    }
                                    ?>
                                </select>
                        </td>


                    </tr>
                    <?php
                    $i++;
            }
            ?>
            </tbody>
        </table>
        <br>
            <?php wp_nonce_field('connect_zoom_account') ?>
            <input type="submit" class="button button-primary" name="save_zoom">
        </form>
        <br>
    </div>

    <?php
}