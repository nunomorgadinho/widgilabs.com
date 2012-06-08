<?php
/*
Plugin Name: WP-TweetButton
Version: 1.7.5
Description: Automatically adds the new Official Twitter Tweet Button to your Blog.Easily Customizable from the Dashboard(with Tweet Syntax, hash tags and much more.).
Author: CoderPlus
Author URI: http://coderplus.com
Plugin URI: http://coderplus.com/2010/08/twitter-tweet-button-plugin-for-wordpress/
wp-tweetbutton
*/
class tweetbutton_custom_user_meta {

function tweetbutton_custom_user_meta() {
    if ( is_admin() ) {
        add_action('show_user_profile', array(&$this,'action_show_user_profile'));
        add_action('edit_user_profile', array(&$this,'action_show_user_profile'));
        add_action('personal_options_update', array(&$this,'action_process_option_update'));
        add_action('edit_user_profile_update', array(&$this,'action_process_option_update'));
    }
}

function action_show_user_profile($user) {
if (function_exists('get_user_meta'))
{$tweetbutton_twitter= get_user_meta($user->ID,'tweetbutton_twitter',true);
 $tweetbutton_twitter_desc= get_user_meta($user->ID,'tweetbutton_twitter_desc',true);
} 
else
{
$tweetbutton_twitter=get_usermeta($user->ID,'tweetbutton_twitter');
$tweetbutton_twitter_desc=get_usermeta($user->ID,'tweetbutton_twitter_desc');
}
    ?>
    <h3>Twitter Info (Will be used on WP-TweetButton)</h3>

    <table class="form-table">
    <tr>
        <th><label for="tweetbutton_twitter">Twitter User name</label></th> 
        <td><input type="text" name="tweetbutton_twitter" id="tweetbutton_twitter" value="<?php echo esc_attr($tweetbutton_twitter); ?>" />
        <td><span class="description">Please enter your Twitter username.Don't add a link or @</span></td>
    </tr>
        <tr>
        <th><label for="tweetbutton_twitter_desc">Twitter Bio</label></th>
        <td><input type="text" name="tweetbutton_twitter_desc" id="tweetbutton_twitter_desc" value="<?php echo esc_attr($tweetbutton_twitter_desc); ?>" />
        <td><span class="description">Please enter a small Bio, ideally your Twitter Bio</span></td>
    </tr>
    </table>
    <?php
}

function action_process_option_update($user_id) {

if (function_exists('update_user_meta'))
{
update_user_meta($user_id, 'tweetbutton_twitter', ( isset($_POST['tweetbutton_twitter']) ? $_POST['tweetbutton_twitter'] : '' ) );
update_user_meta($user_id, 'tweetbutton_twitter_desc', ( isset($_POST['tweetbutton_twitter_desc']) ? $_POST['tweetbutton_twitter_desc'] : '' ) );
}
else

{
update_usermeta($user_id, 'tweetbutton_twitter', ( isset($_POST['tweetbutton_twitter']) ? $_POST['tweetbutton_twitter'] : '' ) );
update_usermeta($user_id, 'tweetbutton_twitter_desc', ( isset($_POST['tweetbutton_twitter_desc']) ? $_POST['tweetbutton_twitter_desc'] : '' ) );
}

}
}
add_action('plugins_loaded', create_function('','global $custom_user_meta_instance; $custom_user_meta_instance = new tweetbutton_custom_user_meta();'));

function new_tweetbutton_admin_action()
{
add_menu_page("Tweet Button", "Tweet Button", 1, basename(__FILE__), "new_tweetbutton_admin");
}

if(is_admin())
{
add_action('admin_menu', 'new_tweetbutton_admin_action');
}
function new_tweetbutton_admin()
{
if($_POST['tweetbutton_hidden'] == 'Y') {
$tweettext = $_POST['tweetbutton_text'];  
update_option('tweetbutton_text', $tweettext);  
$tweetname = $_POST['tweetbutton_name'];  
update_option('tweetbutton_name', $tweetname);
$tweetsyntax = $_POST['tweetbutton_syntax'];  
update_option('tweetbutton_syntax', $tweetsyntax);
$tweetpos = $_POST['tweetbutton_pos'];  
update_option('tweetbutton_pos', $tweetpos);
$tweetloc = $_POST['tweetbutton_loc'];  
update_option('tweetbutton_loc', $tweetloc);
$tweetstyle = $_POST['tweetbutton_style'];  
update_option('tweetbutton_style', $tweetstyle);
$tweetcss = $_POST['tweetbutton_css'];  
update_option('tweetbutton_css', $tweetcss);
$tweetlang = $_POST['tweetbutton_lang'];  
update_option('tweetbutton_lang', $tweetlang);
$tweetrel = $_POST['tweetbutton_rel'];  
update_option('tweetbutton_rel', $tweetrel);
$tweetreldesc = $_POST['tweetbutton_reldesc'];  
update_option('tweetbutton_reldesc', $tweetreldesc);
}
?>
<div class="wrap">  
<h2>Tweet Button Configuration</h2>

<form name="tweetbutton_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
<input type="hidden" name="tweetbutton_hidden" value="Y">
<table cellspacing="3" cellpadding="10">
<tr><td>Twitter Handle</td><td><input type="text" name="tweetbutton_name" value="<?php echo get_option('tweetbutton_name'); ?>" size="40"><span class="description"> Do not use @ or a link to the profile.Use <code>%post_author%</code> if you want to use the post author's twitter username from his <a href="profile.php">profile page</a></span></td><br/></tr>
<tr><td>Tweet Text</td><td><input type="text" name="tweetbutton_text" value="<?php echo get_option('tweetbutton_text');?>" size="40"><span class="description"> Use <code>%post_title%</code> for the post title,<code>%blog_title%</code>  for blog title,<code>%post_tags%</code>  for hashed post tags,<code>%post_cats%</code>  for hashed post categories</span></td></tr>
<tr><td> </td><td> </td></tr>
<tr><td>Tweet Syntax</td>
<td><input type="radio" name="tweetbutton_syntax" value="via" <?php if(get_option('tweetbutton_syntax')=="via") echo "checked" ?>>Tweet Text http://t.co/abcd <b>via @twitter_handle</b><br/><br/>
<input type="radio" name="tweetbutton_syntax" value="rt" <?php if(get_option('tweetbutton_syntax')=="rt") echo "checked" ?>><b>RT @twitter_handle</b> Tweet Text http://t.co/abcd <span class="description"> The traditional way of retweeting :P.But if you use this RT format, then you wont be able to recommend a second twitter account.</span></td></tr>
<tr><td>Button Position</td>
<td><select name="tweetbutton_pos">
  <option value="before" <?php if(get_option('tweetbutton_pos')=="before") echo "selected";?> >Before</option>
  <option value="after" <?php if(get_option('tweetbutton_pos')=="after") echo "selected";?>>After</option>
  <option value="manual"  <?php if(get_option('tweetbutton_pos')=="manual") echo "selected";?>>Manually add the Function call to the Template</option>
</select><span class="description">  If you opt to manually add the function call to the template, then add <code>&lt;?php if(function_exists('the_tweetbutton')) the_tweetbutton();?&gt;</code>where you want the tweet button to appear.</span>
</td></tr>
<tr><td>Where should the button appear? </td>
<td><select name="tweetbutton_loc">
  <option value="all" <?php if(get_option('tweetbutton_loc')=="all") echo "selected";?> >Every Where</option>
  <option value="notall"  <?php if(get_option('tweetbutton_loc')=="notall") echo "selected";?>>Only on Posts or Pages</option>
  <option value="post"  <?php if(get_option('tweetbutton_loc')=="post") echo "selected";?>>Only on Posts</option>
</select>
</td></tr>
<tr><td>Button Style </td> 
<td><select name="tweetbutton_style">
  <option value="vertical" <?php if(get_option('tweetbutton_style')=="vertical") echo "selected";?> >Vertical Counter</option>
  <option value="horizontal"  <?php if(get_option('tweetbutton_style')=="horizontal") echo "selected";?>>Horizontal Counter</option>
  <option value="none"  <?php if(get_option('tweetbutton_style')=="none") echo "selected";?>>No Counter</option>
</select>
</td></tr>
 <tr><td>Styling</td>
<td><input type="text" name="tweetbutton_css" value="<?php echo get_option('tweetbutton_css');?>" size="40"><span class="description"> Add style to the div that surrounds the button E.g. <code>float: left; margin-right: 10px;</code></span></td></tr>

<tr><td>Language </td> 
<td><select name="tweetbutton_lang">
<option value="en" <?php if(get_option('tweetbutton_lang')=="en") echo "selected";?> >English</option>
<option value="fr"  <?php if(get_option('tweetbutton_lang')=="fr") echo "selected";?>>French</option>
<option value="de"  <?php if(get_option('tweetbutton_lang')=="de") echo "selected";?>>German</option>
<option value="es"  <?php if(get_option('tweetbutton_lang')=="es") echo "selected";?>>Spanish</option>
<option value="ja"  <?php if(get_option('tweetbutton_lang')=="ja") echo "selected";?>>Japanese</option>
</select></td></tr></table>

 <p><b>Recommend a Second Twitter Account</b> : </p><span class="description">Your twitter username is recommended by default, so if you want to recommend a different account, then fill the details.If you dont want to recommend anyone else, then leave these fields blank.If you are using the RT Tweet syntax, then the second account wont be recommended(only your twitter handle will be).</span></p>
<table><tr><td>Twitter Handle to be recommended </td><td> <input type="text" name="tweetbutton_rel" value="<?php echo get_option('tweetbutton_rel'); ?>" size="40"><span class="description"> Do not use @ or a link to the profile.Use <code>%post_author%</code> to recommend the post author's twitter profile(from his <a href="profile.php">profile page</a>).</span></td></tr>
<tr><td>A short description of the above account </td><td><input type="text" name="tweetbutton_reldesc" value="<?php echo get_option('tweetbutton_reldesc'); ?>" size="40"><span class="description">Use <code>%post_author_bio%</code> to use the Twitter Bio from the  post author's <a href="profile.php">profile page</a> </span></td></tr></table>



<p class="submit">  
<input type="submit" name="Submit" value="Save Settings" />  
</p>  
</form> 
<h2>Share : </h2>
<p>Did you like this plugin ?, then share it with the world:</p>
<table><tr><td><a href='http://twitter.com/share' rel='nofollow' class='twitter-share-button' data-url='http://coderplus.com/2010/08/twitter-tweet-button-plugin-for-wordpress/' data-text='Official Twitter Tweet Button for Wordpress using the WP Tweet Button Plugin' data-count='vertical' data-via='coderplus'></a></td>
<td width="100"><a style="margin-left:10px;" name='fb_share' rel='nofollow' share_url='http://coderplus.com/2010/08/twitter-tweet-button-plugin-for-wordpress/'  type='box_count'></a>
<script src='http://static.ak.fbcdn.net/connect.php/js/FB.Share' type='text/javascript'></script></td></tr></table>
<p>If you have any questions, suggestions or bug reports regarding the plugin then drop me a comment at <a href="http://coderplus.com/2010/08/twitter-tweet-button-plugin-for-wordpress/" target="_blank">my blog post</a></p>
</script>
</td>
</tr>

</div> 
<?php
}
function tweetbutton_generate($post,$type)
{

$style=get_option('tweetbutton_style');

$lang=get_option('tweetbutton_lang');
$css=get_option('tweetbutton_css');
$tweetrel=get_option('tweetbutton_rel');
$tweetreldesc=get_option('tweetbutton_reldesc');
$tweet_text=get_option('tweetbutton_text');
$twitterhandle=get_option('tweetbutton_name');
if($twitterhandle=="%post_author%") 
{if (function_exists('get_user_meta'))
$twitterhandle=get_user_meta($post->post_author,'tweetbutton_twitter',true);
else $twitterhandle=get_usermeta($post->post_author,'tweetbutton_twitter');
}
if($tweetrel=="%post_author%")
{ 
if (function_exists('get_user_meta'))
$tweetrel=get_user_meta($post->post_author,'tweetbutton_twitter',true);
else
$tweetrel=get_usermeta($post->post_author,'tweetbutton_twitter');
}

if($tweetreldesc=="%post_author_bio%")
{
if (function_exists('get_user_meta'))
$tweetreldesc=get_user_meta($post->post_author,'tweetbutton_twitter_desc',true);
else

$tweetreldesc=get_usermeta($post->post_author,'tweetbutton_twitter_desc');

}
$tweet_pos=get_option('tweetbutton_pos');
$tweet_text=str_replace("%post_title%",$post->post_title,$tweet_text);
$tweet_syntax=get_option('tweetbutton_syntax');
$tweet_loc=get_option('tweetbutton_loc');
if (strpos($tweet_text, '%blog_title%') === false){}else $tweet_text=str_replace("%blog_title%",get_bloginfo('name'),$tweet_text);
$tweet_text=str_replace(" %post_cats%","%post_cats%",$tweet_text);
$tweet_text=str_replace(" %post_tags%","%post_tags%",$tweet_text);
$vialength=0;
if($twitterhandle!=false&&$tweet_syntax=="via"){$vialength=strlen($twitterhandle)+6;}
if($twitterhandle!=false&&$tweet_syntax=="rt"){$viatlength=strlen($twitterhandle)+5;}
$tweetlength=strlen($tweet_text)+21+$vialength;
//tag hash tags
if (strpos($tweet_text, '%post_tags%') === false) {}else 
{
if (strpos($tweet_text, '%post_cats%') === false){} else $tweetlength=$tweetlength-11;
$tweetlength=$tweetlength-11;
$posttags = get_the_tags();
if ($posttags) {
foreach($posttags as $tag) {
$currenttag=" #".str_replace('-','',$tag->slug);
if($tweetlength+strlen($currenttag)<=140)
{
if(strpos($tweet_text,$currenttag)===false) 
{$post_tags=$post_tags.$currenttag; $tweetlength=$tweetlength+strlen($currenttag);}
}
}

}
}
if($post_tags=="") $post_tags=" ";
$tweet_text=str_replace("%post_tags%",$post_tags,$tweet_text);
$tweet_text=trim($tweet_text);
$tweet_text=preg_replace('!\s+!', ' ', $tweet_text);
//category hash tags
$tweetlength=strlen($tweet_text)+21+$vialength;

if (strpos($tweet_text, '%post_cats%') === false){} else
{
$tweetlength=$tweetlength-11;
$post_categories = wp_get_post_categories($post->ID);
if($post_categories)
foreach($post_categories as $c)
{
$cat = get_category( $c );
$currenttag=" #".str_replace('-','',$cat->slug);
if($tweetlength+strlen($currenttag)<=140)
{
if(strpos($tweet_text,$currenttag)===false)
{$post_cats=$post_cats.$currenttag; $tweetlength=$tweetlength+strlen($currenttag);}
}
}

}
if($post_cats=="") $post_cats=" ";
$tweet_text=str_replace("%post_cats%",$post_cats,$tweet_text);


if($tweet_syntax=="via"){$text=$tweet_text;}
else if($twitterhandle!=false) $text="RT @".$twitterhandle." ".$tweet_text; else $text=$tweet_text;
$text=trim($text);
$text=preg_replace('!\s+!', ' ', $text);

$link=get_permalink($post->ID);
$tweetcode= '<div class="tweet_button'.$tweetlength.'" style="'.$css.'"><a href="http://twitter.com/share" rel="nofollow" class="twitter-share-button" data-url="'.$link.'" data-text="'.$text.'" data-count="'.$style.'" data-lang="'.$lang.'" ';
if($tweet_syntax=="via") $tweetcode=$tweetcode.'data-via="'.$twitterhandle.'" ';  

if($tweet_syntax=="rt"&&$twitterhandle==false)
$related=$tweetrel.':'.$tweetreldesc;
else if($tweet_syntax=="rt"&&$twitterhandle!=false)
$related=$twitterhandle;
else if($tweet_syntax=="via"&&$tweetrel!=false)
$related=$tweetrel.':'.$tweetreldesc;
else $related="";
$tweetcode=$tweetcode.' data-related="'.$related.'"></a></div>';
if($type=="button") return $tweetcode;
else
{
$tweet_params='?text='.urlencode($text).'&amp;url='.urlencode($link).'&amp;related='.urlencode($related).'&amp;count='.$style.'&amp;lang='.$lang;
if($tweet_syntax=="rt") $twitterhandle="";
if($twitterhandle!="") $tweet_params=$tweet_params.'&amp;via='.urlencode($twitterhandle);
return 'http://twitter.com/share'.$tweet_params;

}
}
function the_tweetlink()
{

global $post;
$tweetcode=tweetbutton_generate($post,"link");
echo $tweetcode;
}
function the_tweetbutton()
{
if(get_option('tweetbutton_pos')=="manual"&&(get_option('tweetbutton_loc')=="all"||(is_single()||(is_page())&& get_option('tweetbutton_loc')=="notall")||(is_single()&& get_option('tweetbutton_loc')=="post")))
{
global $post;
$tweetcode=tweetbutton_generate($post,"button");
echo $tweetcode;
}
}

function tweetbutton_script() {
    wp_enqueue_script('newtweetbutton','http://platform.twitter.com/widgets.js');            
}    
 
function new_tweetbutton_filter($content)
{

if (is_feed()) return $content;
if(get_option('tweetbutton_pos')=="manual") return $content;
if(!is_single()&&!is_page()&&get_option('tweetbutton_loc')=="notall") return $content;
if(!is_single()&&get_option('tweetbutton_loc')=="post") return $content;
global $post;
$tweetcode=tweetbutton_generate($post,"button");
if(get_option('tweetbutton_pos')=="before") return $tweetcode.$content; 
else if(get_option('tweetbutton_pos')=="after")  return $content.$tweetcode;

}

add_filter('the_content', 'new_tweetbutton_filter');
add_filter('the_excerpt', 'new_tweetbutton_filter');

add_action('init', 'tweetbutton_script');
register_activation_hook(__FILE__, 'tweetbutton_activation');
function tweetbutton_activation()
{
add_option('tweetbutton_text', "%post_title% - %blog_title%");
add_option('tweetbutton_name', "tweetbutton");
add_option('tweetbutton_syntax', "via");
add_option('tweetbutton_pos', "before");
add_option('tweetbutton_loc', "all");
add_option('tweetbutton_style', "vertical");
add_option('tweetbutton_css', "float: right; margin-left: 10px;");
add_option('tweetbutton_lang', "en");
add_option('tweetbutton_rel', "coderplus");
add_option('tweetbutton_reldesc', "Wordpress Tips and more.");


}
?>