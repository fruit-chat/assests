<?php
defined('ABSPATH') or exit;

function fsp_install_tables() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $tableprefix = $wpdb->base_prefix . 'fsp_';

    // ai_logs
    dbDelta("
    CREATE TABLE {$tableprefix}ai_logs (
        id bigint unsigned NOT NULL AUTO_INCREMENT,
        provider varchar(128) NOT NULL,
        prompt text NOT NULL,
        ai_model varchar(64) NOT NULL,
        template_id bigint unsigned NOT NULL,
        endpoint varchar(256) NOT NULL,
        status varchar(32) NOT NULL,
        raw_response text NOT NULL,
        response text,
        body text NOT NULL,
        blog_id bigint unsigned NOT NULL,
        created_at datetime NOT NULL,
        schedule_id bigint unsigned NOT NULL,
        PRIMARY KEY (id) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
    ");

    // ai_templates
    dbDelta("
    CREATE TABLE {$tableprefix}ai_templates (
        id bigint unsigned NOT NULL AUTO_INCREMENT,
        title varchar(256) NOT NULL,
        provider varchar(128) NOT NULL,
        prompt text NOT NULL,
        fallback_text text,
        ai_model varchar(64) NOT NULL,
        type varchar(32) NOT NULL,
        config text NOT NULL,
        blog_id bigint unsigned NOT NULL,
        created_by bigint unsigned NOT NULL,
        PRIMARY KEY (id) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
    ");

    // apps
    dbDelta("
    CREATE TABLE {$tableprefix}apps (
        id int NOT NULL AUTO_INCREMENT,
        social_network varchar(50) NOT NULL,
        name varchar(255) DEFAULT NULL,
        slug varchar(48) DEFAULT NULL,
        data text,
        blog_id int NOT NULL,
        created_by int NOT NULL,
        PRIMARY KEY (id) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
    ");

    // channels
    dbDelta("
    CREATE TABLE {$tableprefix}channels (
        id int NOT NULL AUTO_INCREMENT,
        channel_session_id int NOT NULL,
        name varchar(200) DEFAULT NULL,
        channel_type varchar(30) DEFAULT NULL,
        remote_id varchar(100) DEFAULT NULL,
        picture text,
        status tinyint NOT NULL,
        data text,
        auto_share tinyint DEFAULT '0',
        custom_settings text,
        created_at datetime DEFAULT NULL,
        updated_at datetime DEFAULT NULL,
        is_deleted tinyint DEFAULT '0',
        PRIMARY KEY (id) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
    ");

    // planners
    dbDelta("
    CREATE TABLE {$tableprefix}planners (
        id int NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT NULL,
        post_type varchar(20) DEFAULT NULL,
        status varchar(50) DEFAULT NULL,
        channels text,
        customization_data text,
        share_type varchar(20) DEFAULT NULL,
        sort_by varchar(50) DEFAULT NULL,
        start_at datetime DEFAULT NULL,
        next_execute_at datetime DEFAULT NULL,
        repeating tinyint(1) DEFAULT '1',
        selected_posts text,
        shared_posts text,
        created_by int NOT NULL,
        created_at datetime DEFAULT NULL,
        blog_id int NOT NULL,
        post_filters_date_range_from datetime DEFAULT NULL,
        post_filters_date_range_to datetime DEFAULT NULL,
        post_filters_term bigint unsigned DEFAULT NULL,
        post_filters_skip_oos_products tinyint(1) DEFAULT '1',
        schedule_interval int DEFAULT NULL,
        sleep_time_start time DEFAULT NULL,
        sleep_time_end time DEFAULT NULL,
        weekly text,
        PRIMARY KEY (id) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
    ");

    // schedules
    dbDelta("
    CREATE TABLE {$tableprefix}schedules (
        id int NOT NULL AUTO_INCREMENT,
        wp_post_id bigint NOT NULL,
        blog_id int NOT NULL,
        user_id int NOT NULL,
        channel_id int NOT NULL,
        status varchar(15) DEFAULT NULL,
        edge varchar(128) DEFAULT NULL,
        error_msg varchar(800) DEFAULT NULL,
        send_time timestamp DEFAULT CURRENT_TIMESTAMP,
        remote_post_id varchar(255) DEFAULT NULL,
        visit_count int DEFAULT '0',
        planner_id int DEFAULT NULL,
        is_seen tinyint(1) DEFAULT '0',
        data text,
        customization_data text,
        group_id varchar(50) DEFAULT NULL,
        PRIMARY KEY (id) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
    ");
}

register_activation_hook(__FILE__, 'fsp_install_tables');

// FS Poster
