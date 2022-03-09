<?php
// add free shipping if it exist for the given product 
function signInModal()
{
?>
    <div class="sign-in-modal">
        <ul class="list">
            <li class="list-item">
                <?php
                $currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if (is_user_logged_in()) {
                ?>
                    <a href="<?php echo get_site_url(); ?>/home/members/edit-profile" class="anchor">
                        <i class="fa-light fa-user"></i>
                        <span>My Account </span>
                    </a>
                <?php
                } else {
                ?>
                    <a href="<?php echo get_site_url(); ?>/sign-in?redirect-link=<?php echo $currentLink ?>" class="anchor">
                        <i class="fa-light fa-user"></i>
                        <span>Sign In / Create Account </span>
                    </a>
                <?php } ?>
            </li>
            <li class="list-item">
                <a href="<?php echo get_home_url() . '/home/track-order' ?>" class="anchor">
                    <i class="fa-light fa-cube"></i>
                    <span>Track Orders </span>
                </a>
            </li>
            <li class="list-item">
                <a href="<?php echo get_home_url() ?>/home/members/design-boards" class="anchor">
                    <i class="fa-light fa-heart"></i>
                    <span>Create / Manage Design Boards </span>
                </a>
            </li>
        </ul>
    </div>
<?php
}

add_shortcode('sign-in-modal', 'signInModal');
