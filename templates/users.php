<?php

if (is_user_logged_in(  )  && current_user_can('administrator')){
    get_header();
    ?>
    <div class="wrapper">
        <h1 class="title">Users</h1>
        <form id="users_form" method="post"  >
            <?php 
            $users = get_users();
            usort($users, function ($a, $b) { return strnatcmp($a->ID, $b->ID);});
            foreach ($users as $user) {?>
            
                <div class="user_block" >
                    <span>
                        <label for="<?php echo $user->ID; ?>__login">Display Name</label>
                        <p id="<?php echo $user->ID; ?>show__login" class="show-data"><?php  echo $user->display_name ?></p>
                        <input 
                        id="<?php echo $user->ID; ?>__login"
                        class="user__input d_none"
                        type="text" 
                        name="login"
                        value="<?php  echo $user->display_name ?>">
                    </span>                    
                    <span>
                        <label for="<?php echo $user->ID; ?>__email">Email</label>
                        <p id="<?php echo $user->ID; ?>show__email" class="show-data"><?php  echo $user->user_email ?></p>
                        <input 
                        id="<?php echo $user->ID; ?>__email"
                        class="user__input d_none"
                        type="text" 
                        name="email"
                        value="<?php  echo $user->user_email ?>">
                    </span>
                    <span>
                        <label for="<?php echo $user->ID; ?>__password">Password</label>
                         <p id="<?php echo $user->ID; ?>show__password" class="show-data" style="font-weight: 900;"><?php  echo '**********' ?></p>
                        <input 
                        id="<?php echo $user->ID; ?>__password"
                        class="user__pass d_none"
                        type="password" 
                        name="password"
                        value="<?php  echo $user->user_pass ?>">
                    </span>
                    <span data-id="<?php echo $user->ID ?>"  class="edit-note "><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>

                    <span data-id="<?php echo $user->ID ?>"  class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                </div>
                <br>
            
            <?php }
            get_template_part('template-parts/users/create_new_user');
            ?>
            <p id="add_user_button" class="add-note">Add New User</p>
        </form>
    </div>
    <?php
    
} else {
    wp_safe_redirect(home_url('/login'));
    exit;
}

get_footer() ;
