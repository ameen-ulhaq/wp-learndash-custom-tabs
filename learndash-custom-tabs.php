<?php
/**
* Plugin Name: LearnDash Custom Tabs
* Plugin URI: https://www.abc.com/
* Description: This is LearnDash Custom Tabs.
* Version: 1.0
* Author: Ameen
* Author URI: http://ameen.com/
**/


/**
 * Register and enqueue a custom stylesheet in the WordPress admin.
 */
function wpdocs_enqueue_custom_admin_style() {
    wp_register_style( 'tab-styles',  plugins_url('admin/tab-styles.css',__FILE__ ), array() );
    wp_enqueue_style( 'tab-styles' );
    wp_register_script( 'tab-scripts', plugins_url('admin/tab-scripts.js',__FILE__ ), array() );
    wp_enqueue_script( 'tab-scripts' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );

// custom post type
include plugin_dir_path( __FILE__ ) . 'includes/custom-tabs-cpt.php';

function testf() {
    $lis = get_users(); 
    foreach ($lis as $li) {
        echo '<pre>';
        print_r($li);
        echo '</pre>';
    }
}
// add_action( 'init', 'testf' );



// Adding metabox
function tab_setting_meta_box_add() {
    add_meta_box( 'tab-setting-id', 'Tab Settings', 'tab_setting_callback', 'lms_custom_tabs', 'normal', 'high' );
} 
add_action( 'add_meta_boxes', 'tab_setting_meta_box_add' );

function tab_setting_callback( $post ) {
    ?>
    <div class="wrap">

        <!-- Users -->
        <div class="field-continer">
            <label for="user-selected">Display Tab to: </label>
            <select name='user_selected' id='user-selected'>
                <?php 
                    $userLists = get_users(); 
                    ?> <option value="0"> All Users </option><?php
                    foreach ($userLists as $userList):  ?>
                        <option value="<?php echo esc_attr($userList->ID ); ?>">
                            <?php echo esc_html($userList->display_name); ?>
                        </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Context -->
        <div class="field-continer">
            <label for="context-selected">Display Tab on: </label>
            <select name='context_selected' id='context-selected'>
                <option value="0"> All LMS Pages </option>
                <option value="1"> Course </option>
                <option value="3"> Lesson </option>
                <option value="3"> Topic </option>
                <option value="4"> Quiz </option>
            </select>
        </div>


        <!-- Courses -->
        <div class="field-continer">
            <label for="course-selected">Select Course: </label>
            <select name='course_selected' id='course-selected'>
                <?php 
                    $courseLists = get_posts(array('post_type' => 'sfwd-courses')); 
                    ?> <option value="0"> All Courses </option><?php
                    foreach ($courseLists as $courseList):  ?>
                        <option value="<?php echo esc_attr($courseList->ID); ?>">
                            <?php echo esc_html($courseList->post_title); ?>
                        </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Lessons -->
        <div class="field-continer">
        <label for="lesson-selected">Select Lessons: </label>
        <select name='lesson_selected' id='lesson-selected'>
            <?php 
                $lessonLists = get_posts(array('post_type' => 'sfwd-lessons')); 
                ?> <option value="0"> All Lessons </option><?php
                foreach ($lessonLists as $lessonList): ?>
                    <option value="<?php echo esc_attr($lessonList->ID); ?>">
                        <?php echo esc_html($lessonList->post_title); ?>
                    </option>
            <?php endforeach; ?>
            </select>
        </div>

        <!-- Topics -->
        <div class="field-continer">
            <label for="topic-selected">Select Topic: </label>
            <select name='topic_selected' id='topic-selected'>
                <?php 
                    $topicLists = get_posts(array('post_type' => 'sfwd-topic')); 
                    ?> <option value="0"> All Topics </option><?php
                    foreach ($topicLists as $topicList){ ?>
                        <option value="<?php echo esc_attr($topicList->ID); ?>">
                            <?php echo esc_html($topicList->post_title); ?>
                        </option>
                <?php } ?>
            </select>
        </div>

        <!-- quizs -->
        <div class="field-continer">
            <label for="quiz-selected">Select quiz: </label>
            <select name='quiz_selected' id='quiz-selected'>
            <?php 
                $tabQuizSelectedVal = get_post_meta( $post->ID, 'quiz_selected', true );  
                $quizLists = get_posts(array('post_type' => 'sfwd-quiz')); 
                ?> <option value="0"> All quizs </option><?php
                foreach ($quizLists as $quizList): ?>
                    <option value="<?php echo esc_attr($quizList->ID); ?>"  <?php selected($tabQuizSelectedVal, $quizList->ID ); ?>>
                        <?php echo esc_html($quizList->post_title); ?>
                    </option>
            <?php endforeach; ?>
            </select>
        </div>

        <!-- icon -->
        <div class="field-continer">
            <label for="tab-icon-class">Add class for Icon: </label>
            <?php  $tabIconClassVal = get_post_meta( $post->ID, '_tab_icon_class', true );  ?>
            <input type="text" value="<?= $tabIconClassVal ?>" name="tab_icon_class" id="tab-icon-class">
        </div>
    </div>
    <?php   
}

function tab_setting_save( $post_id ) {

    $metaValues = array(
        '_user_selected' => $_POST['user_selected'],
        '_context_selected' => $_POST['context_selected'], 
        '_course_selected' => $_POST['course_selected'],
        '_lesson_selected' => $_POST['lesson_selected'],
        '_topic_selected' => $_POST['topic_selected'],
        '_quiz_selected' => $_POST['quiz_selected'],
        '_tab_icon_class' => $_POST['tab_icon_class'],
    );
    
    // Set all key/value pairs in $metaValues
    foreach ($metaValues as $metaKey => $metaValue) {
        update_post_meta($post_id, $metaKey, $metaValue);
    }

}
add_action( 'save_post', 'tab_setting_save' );

// adding custom tabs into learndash tabs
function custom_tabs_into_learndash_tabs( $tabs = array(), $context = '', $course_id = 0, $user_id = 0 ) {

    // variables
    $customTabID = $customTabTitle = $customTabContent = $customTabAuthor = $customTabSlug = '';
    // getting all custom tabs from post type
    $customTabs = get_posts(array('post_type' => 'lms_custom_tabs'));
    foreach ($customTabs as $customTab) {
        // assigning valus
        $customTabID = $customTab->ID;
        $customTabSlug = $customTab->post_name;
        $customTabTitle = $customTab->post_title;
        $customTabContent = $customTab->post_content;
        // Making conditions
        If ( 76 === $course_id ) {
        
            // Creating new tabs
            if ( ! isset( $tabs[$customTabSlug] ) ) {
                $tabs[$customTabSlug] = array(
                    'id'      => $customTabSlug,
                    'icon'    => 'ld-downloads-icon',
                    'label'   => $customTabTitle,
                    'content' => $customTabContent,
                );
            }
        }
    }
    return $tabs;
}

add_filter('learndash_content_tabs', 'custom_tabs_into_learndash_tabs', 30, 4);
                



