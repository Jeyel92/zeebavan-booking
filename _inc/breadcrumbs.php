<?php
/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2016.11.17
 * лицензия: MIT
*/
function dimox_breadcrumbs() {
    /* === ОПЦИИ === */
    $text['home'] = 'Home'; // текст ссылки "Главная"
    $text['category'] = '%s'; // текст для страницы рубрики
    $text['search'] = 'Search results for "%s"'; // текст для страницы с результатами поиска
    $text['tag'] = 'Tagged "%s"'; // текст для страницы тега
    $text['author'] = 'By %s'; // текст для страницы автора
    $text['404'] = 'Error 404'; // текст для страницы 404
    $text['page'] = 'Page %s'; // текст 'Страница N'
    $text['cpage'] = 'Comments page %s'; // текст 'Страница комментариев N'
    $text['blog'] = 'Blog'; //текст страницы блога
    $text['default'];

    if (gf('intro_breadcrambs')) {
        $text['default'] = gf('intro_breadcrambs');
    } elseif (gf('intro_breadcrambs', get_page_by_path('shop'))) {
        $text['default'] = gf('intro_breadcrambs', get_page_by_path('shop'));
    }
    if ($text['default']) {
        foreach ($text as $t) {
            $t = $text['default'];
        }
    }

    $wrap_before = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
    $wrap_after = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
    $sep = '/'; // разделитель между "крошками"
    $sep_before = '<span class="sep">'; // тег перед разделителем
    $sep_after = '</span>'; // тег после разделителя
    $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
    $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
    $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
    $before = '<span class="current">'; // тег перед текущей "крошкой"
    $after = '</span>'; // тег после текущей "крошки"
    /* === КОНЕЦ ОПЦИЙ === */

    global $post;
    $home_url = home_url('/');
    $link_before = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $link_after = '</span>';
    $link_attr = ' itemprop="item"';
    $link_in_before = '<span itemprop="name">';
    $link_in_after = '</span>';
    $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
    $frontpage_id = get_option('page_on_front');
    $parent_id = $post->post_parent;
    $sep = ' ' . $sep_before . $sep . $sep_after . ' ';
    $home_link = $link_before . '<a href="' . $home_url . '"' . $link_attr . ' class="home">' . $link_in_before . $text['home'] . $link_in_after . '</a>' . $link_after;

    if (is_home() || is_front_page()) {
        if (is_home() && !is_front_page() && !is_single()) {
            echo $wrap_before;
            if ($show_home_link)
                echo $home_link;
            if ($show_current)
                echo $sep . $before . ($text['default'] ? $text['default'] : $text['blog']) . $after . $wrap_after;
        } else {
            if ($show_home_link)
                echo $home_link;
            if ($show_on_home)
                echo $wrap_before . $home_link . $wrap_after;
        }
    } else {

        echo $wrap_before;
        if ($show_home_link)
            echo $home_link;

        if (is_category()) {
            $cat = get_category(get_query_var('cat'), false);
            if ($cat->parent != 0) {
                $cats = get_category_parents($cat->parent, TRUE, $sep);
                $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr . '>' . $link_in_before . '$2' . $link_in_after . '</a>' . $link_after, $cats);
                if ($show_home_link)
                    echo $sep;
                echo $cats;
            }
            if (get_query_var('paged')) {
                $cat = $cat->cat_ID;
                echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['page'], get_query_var('paged'))) . $after;
            } else {
                if ($show_current)
                    echo $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['category'], single_cat_title('', false))) . $after;
            }

        } elseif (is_search()) {
            if (have_posts()) {
                if ($show_home_link && $show_current)
                    echo $sep;
                if ($show_current)
                    echo $before . ($text['default'] ? $text['default'] : sprintf($text['search'], get_search_query())) . $after;
            } else {
                if ($show_home_link)
                    echo $sep;
                echo $before . ($text['default'] ? $text['default'] : sprintf($text['search'], get_search_query())) . $after;
            }

        } elseif (is_day()) {
            if ($show_home_link)
                echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
            if ($show_current)
                echo $sep . $before . get_the_time('d') . $after;

        } elseif (is_month()) {
            if ($show_home_link)
                echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
            if ($show_current)
                echo $sep . $before . get_the_time('F') . $after;

        } elseif (is_year()) {
            if ($show_home_link && $show_current)
                echo $sep;
            if ($show_current)
                echo $before . get_the_time('Y') . $after;

        } elseif (is_single() && !is_attachment()) {
            if ($show_home_link)
                echo $sep;
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                $lnk = $home_url . '/' . $slug['slug'] . '/';
                if ($slug['slug'] == 'product') {
                    $lnk = get_permalink(woocommerce_get_page_id('shop'));
                }
                printf($link, $lnk, $post_type->labels->singular_name);
                if ($show_current)
                    echo $sep . $before . ($text['default'] ? $text['default'] : get_the_title()) . $after;
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $sep);
                if (!$show_current || get_query_var('cpage'))
                    $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr . '>' . $link_in_before . '$2' . $link_in_after . '</a>' . $link_after, $cats);
                echo $cats;
                if (get_query_var('cpage')) {
                    echo $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['cpage'], get_query_var('cpage'))) . $after;
                } else {
                    if ($show_current)
                        echo $before . ($text['default'] ? $text['default'] : get_the_title()) . $after;
                }
            }

            // custom post type
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            if (get_query_var('paged')) {
                echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['page'], get_query_var('paged'))) . $after;
            } else {
                if ($show_current)
                    echo $sep . $before . ($text['default'] ? $text['default'] : $post_type->label) . $after;
            }

        } elseif (is_attachment()) {
            if ($show_home_link)
                echo $sep;
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat, TRUE, $sep);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr . '>' . $link_in_before . '$2' . $link_in_after . '</a>' . $link_after, $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current)
                echo $sep . $before . ($text['default'] ? $text['default'] : get_the_title()) . $after;

        } elseif (is_page() && !$parent_id) {
            if ($show_current)
                echo $sep . $before . ($text['default'] ? $text['default'] : get_the_title()) . $after;

        } elseif (is_page() && $parent_id) {
            if ($show_home_link)
                echo $sep;
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs) - 1)
                        echo $sep;
                }
            }
            if ($show_current)
                echo $sep . $before . ($text['default'] ? $text['default'] : get_the_title()) . $after;

        } elseif (is_tag()) {
            if (get_query_var('paged')) {
                $tag_id = get_queried_object_id();
                $tag = get_tag($tag_id);
                echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['page'], get_query_var('paged'))) . $after;
            } else {
                if ($show_current)
                    echo $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['tag'], single_tag_title('', false))) . $after;
            }

        } elseif (is_author()) {
            global $author;
            $author = get_userdata($author);
            if (get_query_var('paged')) {
                if ($show_home_link)
                    echo $sep;
                echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . ($text['default'] ? $text['default'] : sprintf($text['page'], get_query_var('paged'))) . $after;
            } else {
                if ($show_home_link && $show_current)
                    echo $sep;
                if ($show_current)
                    echo $before . ($text['default'] ? $text['default'] : sprintf($text['author'], $author->display_name)) . $after;
            }

        } elseif (is_404()) {
            if ($show_home_link && $show_current)
                echo $sep;
            if ($show_current)
                echo $before . $text['404'] . $after;

        } elseif (has_post_format() && !is_singular()) {
            if ($show_home_link)
                echo $sep;
            echo get_post_format_string(get_post_format());
        }

        echo $wrap_after;

    }
}