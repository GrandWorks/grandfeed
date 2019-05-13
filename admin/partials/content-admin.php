<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.grandworks.co
 * @since      1.0.0
 *
 * @package    Grand_feed
 * @subpackage Grand_feed/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form id="details-form" method="POST">
    <h1>GrandFeed</h1>
    <?php wp_nonce_field( 'grandfeed', 'grandfeed_form' ); ?>
    <input type="hidden" name="updated" value="true" />
    <table class="form-table">
        <tr>
            <th scope="row"><label for="show-instagram">Show Instagram</label></th>
            <td><input type="checkbox" name="show-instagram" id="show-instagram" value="1" <?php checked( 1, get_option('grand-feed-show-instagram'));?>>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="show-twitter">Show Twitter</label></th>
            <td><input type="checkbox" name="show-twitter" id="show-twitter" value="1" <?php checked( 1, get_option('grand-feed-show-twitter'));?> ></td>
        </tr>
        <!-- Instagram -->
        <tr>
            <th scope="row"><label for="instagram-client-id">Instagram client id</label></th>
            <td><input data-validate-instagram type="text" name="instagram-client-id" id="instagram-client-id" class="regular-text" value="<?php echo get_option('grand-feed-instagram-client-id'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="instagram-access-token">Instagram access token</label></th>
            <td><input data-validate-instagram type="text" name="instagram-access-token" id="instagram-access-token" class="regular-text" value="<?php echo get_option('grand-feed-instagram-access-token'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="instagram-feed-count">Instagram feed count</label></th>
            <td><input data-validate-instagram type="text" name="instagram-feed-count" id="instagram-feed-count" class="regular-text" value="<?php echo get_option('grand-feed-instagram-feed-count'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <!-- Instagram -->
        <!-- Twitter -->
        <tr>
            <th scope="row"><label for="twitter-oauth">Twitter oauth</label></th>
            <td><input data-validate-twitter type="text" name="twitter-oauth" id="twitter-oauth" class="regular-text" value="<?php echo get_option('grand-feed-twitter-oauth'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="twitter-oauth-secret">Twitter oauth secret</label></th>
            <td><input data-validate-twitter type="text" name="twitter-oauth-secret" id="twitter-oauth-secret" class="regular-text" value="<?php echo get_option('grand-feed-twitter-oauth-secret'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="twitter-consumer-key">Twitter consumer key</label></th>
            <td><input data-validate-twitter type="text" name="twitter-consumer-key" id="twitter-consumer-key" class="regular-text" value="<?php echo get_option('grand-feed-twitter-consumer-key'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="twitter-consumer-secret">Twitter consumer secret</label></th>
            <td><input data-validate-twitter type="text" name="twitter-consumer-secret" id="twitter-consumer-secret" class="regular-text" value="<?php echo get_option('grand-feed-twitter-consumer-secret'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="twitter-feed-count">Twitter feed count</label></th>
            <td><input data-validate-twitter type="text" name="twitter-feed-count" id="twitter-feed-count" class="regular-text" value="<?php echo get_option('grand-feed-twitter-feed-count'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="post-feed-count">Post feed count</label></th>
            <td><input type="text" name="post-feed-count" id="post-feed-count" class="regular-text" value="<?php echo get_option('grand-feed-post-feed-count'); ?>">
            <span class="error"></span>
            </td>
        </tr>
        <!-- Twitter -->
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    </p>
</form>
<form method="post">
    <?php wp_nonce_field( 'grandfeed', 'grandfeed_form_reset' ); ?>
    <input type="hidden" name="fetch-data" value="true" />
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Fetch Data">
    </p>
</form>