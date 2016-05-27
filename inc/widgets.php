<?php
/*
	Add widget capabilities
	goal : less widgets !
*/

function wpgithub_register_widgets() {
  register_widget('Widget_Profile');
  register_widget('Widget_Repos');
  register_widget('Widget_Commits');
  register_widget('Widget_Issues');
  register_widget('Widget_Gists');
}

add_action('widgets_init', 'wpgithub_register_widgets');

/*
 * Profile widget.
 */

class Widget_Profile extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'Widget_Profile', // Base ID
      __('WP Github Profile', 'wp-github'), // Name
      array('description' => __('Github Profile', 'wp-github'),) // Args
    );
  }

  function Widget_Profile() {
    $widget_ops = array('description' => __('Displays the Github user profile.'));
    $this->WP_Widget(FALSE, __('Github Profile'), $widget_ops);
  }

  public function form($instance) {
    $title = $this->get_title($instance);
    $username = $this->get_username($instance);

    ?>
    <p>
      <label
        for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo $title; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
             name="<?php echo $this->get_field_name('username'); ?>" type="text"
             value="<?php echo $username; ?>"/>
    </p>
    <?php
  }

  private function get_title($instance) {
    return empty($instance['title']) ? 'My Github Profile' : apply_filters('widget_title', $instance['title']);
  }

  private function get_username($instance) {
    return empty($instance['username']) ? get_option('wpgithub_defaultuser', 'seinoxygen') : $instance['username'];
  }

  /**
   * Front-end display of widget.
   *
   * @param array $args Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget($args, $instance) {
    extract($args);

    $title = $this->get_title($instance);
    $username = $this->get_username($instance);

    echo $args['before_widget'];
    echo $args['before_title'] . $title . $args['after_title'];

    // Init the cache system.
    $cache = new WpGithubCache();
    // Set custom timeout in seconds.
    $cache->timeout = get_option('wpgithub_cache_time', 600);

    $profile = $cache->get($username . '.json');
    if ($profile == NULL) {
      $github = new Github($username);
      $profile = $github->get_profile();
      $cache->set($username . '.json', $profile);
    }

    echo '<div class="wpgithub-profile">';
    echo '<div class="wpgithub-user">';
    echo '<a href="' . $profile->html_url . '" title="View ' . $username . '\'s Github"><img width="80" src="' . $profile->avatar_url . '?s=56" alt="View ' . $username . '\'s Github" /></a>';
    echo '<h3 class="wpgithub-username"><a href="' . $profile->html_url . '" title="View ' . $username . '\'s Github">' . $username . '</a></h3>';
    echo '<p class="wpgithub-name">' . $profile->name . '</p>';
    echo '<p class="wpgithub-location">' . $profile->location . '</p>';
    echo '</div>';
    echo '<a class="wpgithub-bblock" href="https://github.com/' . $username . '?tab=repositories"><span class="wpgithub-count">' . $profile->public_repos . '</span><span class="wpgithub-text">Public Repos</span></a>';
    echo '<a class="wpgithub-bblock" href="https://gist.github.com/' . $username . '"><span class="wpgithub-count">' . $profile->public_gists . '</span><span class="wpgithub-text">Public Gists</span></a>';
    echo '<a class="wpgithub-bblock" href="https://github.com/' . $username . '/followers"><span class="wpgithub-count">' . $profile->followers . '</span><span class="wpgithub-text">Followers</span></a>';
    echo '</div>';

    echo $args['after_widget'];
  }
}



/**
 * Class Widget_Repos
 */
class Widget_Repos extends WP_Widget {

  /**
   * Widget_Repos constructor.
   */
  function __construct() {
    parent::__construct(
      'Widget_Repos', // Base ID
      __('WP Github repos', 'wp-github'), // Name
      array('description' => __('Github Repositories', 'wp-github'),) // Args
    );
  }

  function Widget_Repos() {
    $widget_ops = array('description' => __('Displays the repositories from a specific user.'));
    $this->WP_Widget(FALSE, __('Github Repositories'), $widget_ops);
  }

  function form($instance) {
    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $project_count = $this->get_project_count($instance);

    ?>
    <p>
      <label
        for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo $title; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
             name="<?php echo $this->get_field_name('username'); ?>" type="text"
             value="<?php echo $username; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('project_count'); ?>"><?php _e('Number of projects to show:'); ?> </label>
      <input id="<?php echo $this->get_field_id('project_count'); ?>"
             name="<?php echo $this->get_field_name('project_count'); ?>"
             type="text"
             value="<?php echo $project_count; ?>" size="3"/>
    </p>
    <?php
  }

  private function get_title($instance) {
    return empty($instance['title']) ? 'My Github Projects' : apply_filters('widget_title', $instance['title']);
  }

  private function get_username($instance) {
    return empty($instance['username']) ? get_option('wpgithub_defaultuser', 'seinoxygen') : $instance['username'];
  }

  private function get_project_count($instance) {
    return empty($instance['project_count']) ? 5 : $instance['project_count'];
  }

  function widget($args, $instance) {
    extract($args);

    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $project_count = $this->get_project_count($instance);

    echo $args['before_widget'];
    echo $args['before_title'] . $title . $args['after_title'];

    // Init the cache system.
    $cache = new WpGithubCache();
    // Set custom timeout in seconds.
    $cache->timeout = get_option('wpgithub_cache_time', 600);

    $repositories = $cache->get($username . '.repositories.json');
    if ($repositories == NULL) {
      $github = new Github($username);
      $repositories = $github->get_repositories();
      $cache->set($username . '.repositories.json', $repositories);
    }

    if ($repositories == NULL || count($repositories) == 0) {
      echo $username . ' does not have any public repositories.';
    }
    else {
      $repositories = array_slice($repositories, 0, $project_count);
      echo '<ul>';
      foreach ($repositories as $repository) {
        echo '<li><a target="_blank" href="' . $repository->html_url . '" title="' . $repository->description . '">' . $repository->name . '</a></li>';
      }
      echo '</ul>';
    }

    echo $args['after_widget'];
  }
}


/**
 * Class Widget_Commits
 */
class Widget_Commits extends WP_Widget {

  function __construct() {
    parent::__construct(
      'Widget_Commits', // Base ID
      __('WP Github commits', 'wp-github'), // Name
      array('description' => __('Github Commits', 'wp-github'),) // Args
    );
  }

  function Widget_Commits() {
    $widget_ops = array('description' => __('Displays latests commits from a Github repository.','wp-github'));
    $this->WP_Widget(FALSE, __('Github Commits'), $widget_ops);
  }

  function form($instance) {
    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $repository = $this->get_repository($instance);
    $commit_count = $this->get_commit_count($instance);

    ?>
    <p>
      <label
        for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wp-github'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo $title; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
             name="<?php echo $this->get_field_name('username'); ?>" type="text"
             value="<?php echo $username; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('repository'); ?>"><?php _e('Github Repository:'); ?> (leave empty for all) </label>
      <input class="widefat"
             id="<?php echo $this->get_field_id('repository'); ?>"
             name="<?php echo $this->get_field_name('repository'); ?>"
             type="text"
             value="<?php echo $repository; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('commit_count'); ?>"><?php _e('Number of commits to show:','wp-github'); ?> </label>
      <input id="<?php echo $this->get_field_id('commit_count'); ?>"
             name="<?php echo $this->get_field_name('commit_count'); ?>"
             type="text"
             value="<?php echo $commit_count; ?>" size="3"/>
    </p>
    <?php
  }

  private function get_title($instance) {
    return empty($instance['title']) ? 'My Github Commits' : apply_filters('widget_title', $instance['title']);
  }

  private function get_username($instance) {
    return empty($instance['username']) ? get_option('wpgithub_defaultuser', '') : $instance['username'];
  }

  private function get_repository($instance) {
    return empty($instance['repository']) ? '' : $instance['repository'];
  }

  private function get_commit_count($instance) {
    return empty($instance['commit_count']) ? 5 : $instance['commit_count'];
  }

  function widget($args, $instance) {
    extract($args);

    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $repository = $this->get_repository($instance);
    $commit_count = $this->get_commit_count($instance);

    echo $args['before_widget'];
    echo $args['before_title'] . $title . $args['after_title'];

    // Init the cache system.
    $cache = new WpGithubCache();
    // Set custom timeout in seconds.
    $cache->timeout = get_option('wpgithub_cache_time', 600);

    $commits = $cache->get($username . '.' . $repository . '.commits.json');
    if ($commits == NULL) {
      $github = new Github($username, $repository);
      $commits = $github->get_commits();
      $cache->set($username . '.' . $repository . '.commits.json', $commits);
    }

    if ($commits == NULL || count($commits) == 0) {
      echo $username . ' does not have any public commits.';
    }
    else {
      $commits = array_slice($commits, 0, $commit_count);
      echo '<ul>';
      foreach ($commits as $commit) {
        echo '<li><a target="_blank" href="' . $commit->html_url . '" title="' . $commit->commit->message . '">' . $commit->commit->message . '</a></li>';
      }
      echo '</ul>';
    }

    echo $args['after_widget'];
  }
}

/*
 * Issues widget.
 */

class Widget_Issues extends WP_Widget {
  function __construct() {
    parent::__construct(
      'Widget_Issues', // Base ID
      __('WP Github issues', 'wp-github'), // Name
      array('description' => __('Github Issues', 'wp-github'),) // Args
    );
  }

  function Widget_Issues() {
    $widget_ops = array('description' => __('Displays latests issues from a Github repository.'));
    $this->WP_Widget(FALSE, __('Github Issues'), $widget_ops);
  }

  function form($instance) {
    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $repository = $this->get_repository($instance);
    $issue_count = $this->get_issue_count($instance);

    ?>
    <p>
      <label
        for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo $title; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
             name="<?php echo $this->get_field_name('username'); ?>" type="text"
             value="<?php echo $username; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('repository'); ?>"><?php _e('Github Repository:'); ?></label>
      <input class="widefat"
             id="<?php echo $this->get_field_id('repository'); ?>"
             name="<?php echo $this->get_field_name('repository'); ?>"
             type="text"
             value="<?php echo $repository; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('issue_count'); ?>"><?php _e('Number of issues to show:'); ?> </label>
      <input id="<?php echo $this->get_field_id('issue_count'); ?>"
             name="<?php echo $this->get_field_name('issue_count'); ?>"
             type="text"
             value="<?php echo $issue_count; ?>" size="3"/>
    </p>
    <?php
  }

  private function get_title($instance) {
    return empty($instance['title']) ? 'My Github Issues' : apply_filters('widget_title', $instance['title']);
  }

  private function get_username($instance) {
    return empty($instance['username']) ? get_option('wpgithub_defaultuser', '') : $instance['username'];
  }

  private function get_repository($instance) {
    return empty($instance['repository']) ? get_option('wpgithub_defaultrepo', '') : $instance['repository'];
  }

  private function get_issue_count($instance) {
    return empty($instance['issue_count']) ? 5 : $instance['issue_count'];
  }

  function widget($args, $instance) {
    extract($args);

    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $repository = $this->get_repository($instance);
    $issue_count = $this->get_issue_count($instance);

    echo $args['before_widget'];
    echo $args['before_title'] . $title . $args['after_title'];

    // Init the cache system.
    $cache = new WpGithubCache();
    // Set custom timeout in seconds.
    $cache->timeout = get_option('wpgithub_cache_time', 600);

    $issues = $cache->get($username . '.' . $repository . '.issues.json');
    if ($issues == NULL) {
      $github = new Github($username, $repository);
      $issues = $github->get_issues();
      $cache->set($username . '.' . $repository . '.issues.json', $issues);
    }

    if ($issues == NULL || count($issues) == 0) {
      echo $username . ' does not have any public issues.';
    }
    else {
      $issues = array_slice($issues, 0, $issue_count);
      echo '<ul>';
      foreach ($issues as $issue) {
        echo '<li><a target="_blank" href="' . $issue->html_url . '" title="' . $issue->title . '">' . $issue->title . '</a></li>';
      }
      echo '</ul>';
    }

    echo $args['after_widget'];
  }
}

/*
 * Gists widget.
 */

class Widget_Gists extends WP_Widget {

  function __construct() {
    parent::__construct(
      'Widget_Gists', // Base ID
      __('WP Github gists', 'wp-github'), // Name
      array('description' => __('Github Gists', 'wp-github'),) // Args
    );
  }

  function Widget_Gists() {
    $widget_ops = array('description' => __('Displays latests gists from a Github user.'));
    $this->WP_Widget(FALSE, __('Github Gists'), $widget_ops);
  }

  function form($instance) {
    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $gists_count = $this->get_gists_count($instance);

    ?>
    <p>
      <label
        for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo $title; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
      <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
             name="<?php echo $this->get_field_name('username'); ?>" type="text"
             value="<?php echo $username; ?>"/>
    </p>
    <p>
      <label
        for="<?php echo $this->get_field_id('gists_count'); ?>"><?php _e('Number of gists to show:'); ?> </label>
      <input id="<?php echo $this->get_field_id('gists_count'); ?>"
             name="<?php echo $this->get_field_name('gists_count'); ?>"
             type="text"
             value="<?php echo $gists_count; ?>" size="3"/>
    </p>
    <?php
  }

  private function get_title($instance) {
    return empty($instance['title']) ? 'My Github Gists' : apply_filters('widget_title', $instance['title']);
  }

  private function get_username($instance) {
    return empty($instance['username']) ? get_option('wpgithub_defaultuser', 'seinoxygen') : $instance['username'];
  }

  private function get_gists_count($instance) {
    return empty($instance['gists_count']) ? 5 : $instance['gists_count'];
  }

  function widget($args, $instance) {
    extract($args);

    $title = $this->get_title($instance);
    $username = $this->get_username($instance);
    $gists_count = $this->get_gists_count($instance);

    echo $args['before_widget'];
    echo $args['before_title'] . $title . $args['after_title'];

    // Init the cache system.
    $cache = new WpGithubCache();
    // Set custom timeout in seconds.
    $cache->timeout = get_option('wpgithub_cache_time', 600);

    $gists = $cache->get($username . '.gists.json');
    if ($gists == NULL) {
      $github = new Github($username);
      $gists = $github->get_gists();
      $cache->set($username . '.gists.json', $gists);
    }

    if ($gists == NULL || count($gists) == 0) {
      echo $username . ' does not have any public gists.';
    }
    else {
      $gists = array_slice($gists, 0, $gists_count);
      echo '<ul>';
      foreach ($gists as $gist) {
        echo '<li><a target="_blank" href="' . $gist->html_url . '" title="' . $gist->description . '">' . $gist->description . '</a></li>';
      }
      echo '</ul>';
    }

    echo $args['after_widget'];
  }
}
