<?php
/**
 * @Template: class-mage-menu-edit.php
 * @since: 1.0.0
 * @author: Wider Themes
 * @descriptions:
 * @create: 22-Nov-17
 */
if (!defined('ABSPATH')) {
    die();
}

class EFramework_Mega_Menu_Edit_Walker extends Walker_Nav_Menu_Edit
{
    protected $mega_locations;

//    private $extra_menu= $extra_menu_custom;

    function __construct()
    {

        $this->megamenus = get_posts(array(
            'post_type' => 'evolt-mega-menu',
            'posts_per_page' => '-1'
        ));
        $this->walker_args = array(
            'depth' => 0,
            'child_of' => 0,
            'selected' => 0,
            'value_field' => 'ID'
        );
    }

    /**
     * Start the element output.
     *
     * @see Walker_Nav_Menu::start_el()
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $item_output = '';
        parent::start_el($item_output, $item, $depth, $args, $id);

        // Adding new Fields
        $item_output = str_replace('<fieldset class="field-move', $this->get_fields($item, $depth, $args, $id) . '<fieldset class="field-move', $item_output);

        $output .= $item_output;
    }

    function get_fields($item, $depth = 0, $args = array(), $id = 0)
    {
        $enable_megamenu = apply_filters('evolt_enable_megamenu', false);
        $enable_ongpage_option = apply_filters('evolt_enable_onepage', true);
        $this->mega_locations = apply_filters('evolt_locations', array('primary'));
        $check_mega = true;
        $nav_menu_selected_id = isset($_REQUEST['menu']) ? (int)$_REQUEST['menu'] : intval(get_user_option('nav_menu_recently_edited'));
        $locations = get_registered_nav_menus();
        $menu_locations = get_nav_menu_locations();
        $key = array_search($nav_menu_selected_id, $menu_locations, true);
        if (in_array($nav_menu_selected_id, $menu_locations) && isset($locations[$key]) && in_array($key, $this->mega_locations)) {
            $check_mega = true;
        }

        ob_start();

        $item_id = esc_attr($item->ID);
        ?>

    <?php if (0 === $depth && $check_mega && $enable_megamenu === true) : ?>
        <p class="description description-wide">
            <label for="edit-menu-item-evolt-megaprofile-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Select Mega Menu', EVOLT_TEXT_DOMAIN); ?><br/>
                <select id="edit-menu-item-evolt-megaprofile-<?php echo esc_attr($item_id); ?>" class="widefat"
                        name="menu-item-evolt-megaprofile[<?php echo esc_attr($item_id); ?>]">
                    <option value="0"><?php esc_html_e('None', EVOLT_TEXT_DOMAIN) ?></option>
                    <?php
                    $r = $this->walker_args;
                    $r['selected'] = $item->evolt_megaprofile;
                    echo walk_page_dropdown_tree($this->megamenus, $r['depth'], $r);
                    ?>
                </select>
            </label>
        </p>
    <?php endif; ?>
    <?php //if ($check_mega): ?>
        <p class="description description-wide">
            <label for="edit-menu-item-evolt-icon-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Icon', EVOLT_TEXT_DOMAIN); ?><br/>
                <select id="edit-menu-item-evolt-icon-<?php echo esc_attr($item_id); ?>"
                        class="widefat evolt-icon-picker"
                        name="menu-item-evolt-icon[<?php echo esc_attr($item_id); ?>]">
                    <option value="" <?php selected('', esc_attr($item->evolt_icon)) ?>><?php esc_html_e('No Icons', EVOLT_TEXT_DOMAIN) ?></option>
                    <?php $arr = $this->evolt_iconpicker_fontawesome();
                    foreach ($arr as $group => $icons) { ?>
                        <optgroup label="<?php echo esc_attr($group); ?>">
                            <?php foreach ($icons as $key => $label) {
                                $class_key = key($label); ?>
                                <option value="<?php echo esc_attr($class_key); ?>" <?php selected($class_key, esc_attr($item->evolt_icon)) ?>><?php echo esc_html(current($label)); ?></option>
                            <?php } ?>
                        </optgroup>
                    <?php } ?>
                </select>
            </label>
        </p>
    <?php //endif; ?>

    <?php if ($enable_ongpage_option && 0 === $depth) : ?>

        <p class="description description-wide">
            <label for="menu-item-evolt-menu-marker-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Marker', EVOLT_TEXT_DOMAIN); ?><br/>
                <input type="text" min="0" id="menu-item-evolt-menu-marker-<?php echo esc_attr($item_id); ?>"
                        class="widefat menu-item-evolt-menu-marker"
                        name="menu-item-evolt-menu-marker[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->evolt_menu_marker); ?>" />
            </label>
        </p>

        <p class="description description-wide">
            <label for="menu-item-evolt-onepage-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('One page', EVOLT_TEXT_DOMAIN); ?><br/>
                <select id="menu-item-evolt-onepage-<?php echo esc_attr($item_id); ?>"
                        class="widefat menu-item-evolt-onepage"
                        name="menu-item-evolt-onepage[<?php echo esc_attr($item_id); ?>]">
                    <option value="no-one-page" <?php selected(esc_attr($item->evolt_onepage), 'no-one-page') ?>><?php esc_html_e('No', EVOLT_TEXT_DOMAIN) ?></option>
                    <option value="is-one-page" <?php selected(esc_attr($item->evolt_onepage), 'is-one-page') ?>><?php esc_html_e('Yes', EVOLT_TEXT_DOMAIN) ?></option>
                </select>
            </label>
        </p>
        <p class="description description-wide">
            <label for="menu-item-evolt-onepage-offset-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('One page offset', EVOLT_TEXT_DOMAIN); ?><br/>
                <input type="number" min="0" id="menu-item-evolt-onepage-offset-<?php echo esc_attr($item_id); ?>"
                        class="widefat menu-item-evolt-onepage-offset"
                        name="menu-item-evolt-onepage-offset[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->evolt_onepage_offset); ?>" />
            </label>
        </p>
        <p class="description description-wide">
            <label for="menu-item-evolt-custom-class-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Custom class', EVOLT_TEXT_DOMAIN); ?><br/>
                <input type="text" min="0" id="menu-item-evolt-custom-class-<?php echo esc_attr($item_id); ?>"
                        class="widefat menu-item-evolt-custom-class"
                        name="menu-item-evolt-custom-class[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->evolt_custom_class); ?>" />
            </label>
        </p>
    <?php endif;
        global $extra_menu_custom;
        if (!empty($extra_menu_custom)) {
            foreach ($extra_menu_custom as $key => $fields) {
                $fields["allow_primary"] = isset($fields["allow_primary"]) ? $fields["allow_primary"] : true;
                if (in_array($depth, $fields['lever_support']) && (($check_mega === true && $fields["allow_primary"] === true) || $fields["allow_primary"] === false)):
                    ?>
                    <p class="description description-wide">
                        <label for="menu-item-<?php echo esc_attr($key) ?>-<?php echo esc_attr($item_id); ?>">
                            <?php echo esc_attr($fields['label']) ?><br/>
                            <select id="menu-item-<?php echo esc_attr($key) ?>-<?php echo esc_attr($item_id); ?>"
                                    class="widefat menu-item-<?php echo esc_attr($key) ?>"
                                    name="menu-item-<?php echo esc_attr($key) ?>[<?php echo esc_attr($item_id); ?>]">
                                <?php
                                foreach ($fields["options"] as $val => $text) {
                                    ?>
                                    <option value="<?php echo esc_attr($val) ?>" <?php selected(esc_attr($item->$key), $val) ?>><?php echo esc_attr($text) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </label>
                    </p>
                <?php
                endif;
            }
        }

        ?>
        <script>
            jQuery('.evolt-iconpicker').fontIconPicker();
        </script>

        <?php
        return ob_get_clean();
    }

    function evolt_iconpicker_fontawesome()
    {
        // Categorized icons ( you can also output simple array ( key=> value ), where key = icon class, value = icon readable name ).
        /**
         * @version 4.6.3
         */
        $icons = array(
            'New in 4.6' => array(
                array('fa fa-instagram' => 'Instagram'),
                array('fa fa-gitlab' => 'GitLab'),
                array('fa fa-wpbeginner' => 'WPBeginner'),
                array('fa fa-wpforms' => 'WPForms'),
                array('fa fa-envira' => 'Envira Gallery(leaf)'),
                array('fa fa-universal-access' => 'Universal Access'),
                array('fa fa-wheelchair-alt' => 'Wheelchair Alt'),
                array('fa fa-question-circle-o' => 'Question Circle Outlined'),
                array('fa fa-blind' => 'Blind'),
                array('fa fa-audio-description' => 'Audio Description'),
                array('fa fa-volume-control-phone' => 'Volume Control Phone'),
                array('fa fa-braille' => 'Braille'),
                array('fa fa-assistive-listening-systems' => 'Assistive Listening Systems'),
                array('fa fa-american-sign-language-interpreting' => 'American Sign Language Interpreting(asl-interpreting)'),
                array('fa fa-deaf' => 'Deaf(deafness, hard-of-hearing)'),
                array('fa fa-glide' => 'Glide'),
                array('fa fa-glide-g' => 'Glide G'),
                array('fa fa-sign-language' => 'Sign Language(signing)'),
                array('fa fa-low-vision' => 'Low Vision'),
                array('fa fa-viadeo' => 'Viadeo'),
                array('fa fa-viadeo-square' => 'Viadeo Square'),
                array('fa fa-snapchat' => 'Snapchat'),
                array('fa fa-snapchat-ghost' => 'Snapchat Ghost'),
                array('fa fa-snapchat-square' => 'Snapchat Square'),
                array('fa fa-pied-piper' => 'Pied Piper Logo'),
                array('fa fa-first-order' => 'First Order'),
                array('fa fa-yoast' => 'Yoast'),
                array('fa fa-themeisle' => 'ThemeIsle'),
                array('fa fa-google-plus-official' => 'Google Plus Official(google-plus-circle)'),
                array('fa fa-font-awesome' => 'Font Awesome(fa)'),
            ),
            'Web Application Icons' => array(
                array('fa fa-glass' => 'Glass(martini, drink, bar, alcohol, liquor)'),
                array('fa fa-music' => 'Music(note, sound)'),
                array('fa fa-search' => 'Search(magnify, zoom, enlarge, bigger)'),
                array('fa fa-envelope-o' => 'Envelope Outlined(email, support, e-mail, letter, mail, notification)'),
                array('fa fa-heart' => 'Heart(love, like, favorite)'),
                array('fa fa-star' => 'Star(award, achievement, night, rating, score, favorite)'),
                array('fa fa-star-o' => 'Star Outlined(award, achievement, night, rating, score, favorite)'),
                array('fa fa-user' => 'User(person, man, head, profile)'),
                array('fa fa-film' => 'Film(movie)'),
                array('fa fa-check' => 'Check(checkmark, done, todo, agree, accept, confirm, tick, ok)'),
                array('fa fa-times' => 'Times(close, exit, x, cross)(remove, close)'),
                array('fa fa-search-plus' => 'Search Plus(magnify, zoom, enlarge, bigger)'),
                array('fa fa-search-minus' => 'Search Minus(magnify, minify, zoom, smaller)'),
                array('fa fa-power-off' => 'Power Off(on)'),
                array('fa fa-signal' => 'signal(graph, bars)'),
                array('fa fa-cog' => 'cog(settings)(gear)'),
                array('fa fa-trash-o' => 'Trash Outlined(garbage, delete, remove, trash, hide)'),
                array('fa fa-home' => 'home(main, house)'),
                array('fa fa-clock-o' => 'Clock Outlined(watch, timer, late, timestamp)'),
                array('fa fa-road' => 'road(street)'),
                array('fa fa-download' => 'Download(import)'),
                array('fa fa-inbox' => 'inbox'),
                array('fa fa-refresh' => 'refresh(reload, sync)'),
                array('fa fa-lock' => 'lock(protect, admin)'),
                array('fa fa-flag' => 'flag(report, notification, notify)'),
                array('fa fa-headphones' => 'headphones(sound, listen, music, audio)'),
                array('fa fa-volume-off' => 'volume-off(audio, mute, sound, music)'),
                array('fa fa-volume-down' => 'volume-down(audio, lower, quieter, sound, music)'),
                array('fa fa-volume-up' => 'volume-up(audio, higher, louder, sound, music)'),
                array('fa fa-qrcode' => 'qrcode(scan)'),
                array('fa fa-barcode' => 'barcode(scan)'),
                array('fa fa-tag' => 'tag(label)'),
                array('fa fa-tags' => 'tags(labels)'),
                array('fa fa-book' => 'book(read, documentation)'),
                array('fa fa-bookmark' => 'bookmark(save)'),
                array('fa fa-print' => 'print'),
                array('fa fa-camera' => 'camera(photo, picture, record)'),
                array('fa fa-video-camera' => 'Video Camera(film, movie, record)'),
                array('fa fa-picture-o' => 'Picture Outlined(photo, image)'),
                array('fa fa-pencil' => 'pencil(write, edit, update)'),
                array('fa fa-map-marker' => 'map-marker(map, pin, location, coordinates, localize, address, travel, where, place)'),
                array('fa fa-adjust' => 'adjust(contrast)'),
                array('fa fa-tint' => 'tint(raindrop, waterdrop, drop, droplet)'),
                array('fa fa-pencil-square-o' => 'Pencil Square Outlined(write, edit, update)(edit)'),
                array('fa fa-share-square-o' => 'Share Square Outlined(social, send, arrow)'),
                array('fa fa-check-square-o' => 'Check Square Outlined(todo, done, agree, accept, confirm, ok)'),
                array('fa fa-arrows' => 'Arrows(move, reorder, resize)'),
                array('fa fa-plus-circle' => 'Plus Circle(add, new, create, expand)'),
                array('fa fa-minus-circle' => 'Minus Circle(delete, remove, trash, hide)'),
                array('fa fa-times-circle' => 'Times Circle(close, exit, x)'),
                array('fa fa-check-circle' => 'Check Circle(todo, done, agree, accept, confirm, ok)'),
                array('fa fa-question-circle' => 'Question Circle(help, information, unknown, support)'),
                array('fa fa-info-circle' => 'Info Circle(help, information, more, details)'),
                array('fa fa-crosshairs' => 'Crosshairs(picker)'),
                array('fa fa-times-circle-o' => 'Times Circle Outlined(close, exit, x)'),
                array('fa fa-check-circle-o' => 'Check Circle Outlined(todo, done, agree, accept, confirm, ok)'),
                array('fa fa-ban' => 'ban(delete, remove, trash, hide, block, stop, abort, cancel)'),
                array('fa fa-share' => 'Share(mail-forward)'),
                array('fa fa-plus' => 'plus(add, new, create, expand)'),
                array('fa fa-minus' => 'minus(hide, minify, delete, remove, trash, hide, collapse)'),
                array('fa fa-asterisk' => 'asterisk(details)'),
                array('fa fa-exclamation-circle' => 'Exclamation Circle(warning, error, problem, notification, alert)'),
                array('fa fa-gift' => 'gift(present)'),
                array('fa fa-leaf' => 'leaf(eco, nature, plant)'),
                array('fa fa-fire' => 'fire(flame, hot, popular)'),
                array('fa fa-eye' => 'Eye(show, visible, views)'),
                array('fa fa-eye-slash' => 'Eye Slash(toggle, show, hide, visible, visiblity, views)'),
                array('fa fa-exclamation-triangle' => 'Exclamation Triangle(warning, error, problem, notification, alert)(warning)'),
                array('fa fa-plane' => 'plane(travel, trip, location, destination, airplane, fly, mode)'),
                array('fa fa-calendar' => 'calendar(date, time, when, event)'),
                array('fa fa-random' => 'random(sort, shuffle)'),
                array('fa fa-comment' => 'comment(speech, notification, note, chat, bubble, feedback, message, texting, sms)'),
                array('fa fa-magnet' => 'magnet'),
                array('fa fa-retweet' => 'retweet(refresh, reload, share)'),
                array('fa fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)'),
                array('fa fa-folder' => 'Folder'),
                array('fa fa-folder-open' => 'Folder Open'),
                array('fa fa-arrows-v' => 'Arrows Vertical(resize)'),
                array('fa fa-arrows-h' => 'Arrows Horizontal(resize)'),
                array('fa fa-bar-chart' => 'Bar Chart(graph, analytics)(bar-chart-o)'),
                array('fa fa-camera-retro' => 'camera-retro(photo, picture, record)'),
                array('fa fa-key' => 'key(unlock, password)'),
                array('fa fa-cogs' => 'cogs(settings)(gears)'),
                array('fa fa-comments' => 'comments(conversation, notification, notes, message, texting, sms, chat)'),
                array('fa fa-thumbs-o-up' => 'Thumbs Up Outlined(like, approve, favorite, agree, hand)'),
                array('fa fa-thumbs-o-down' => 'Thumbs Down Outlined(dislike, disapprove, disagree, hand)'),
                array('fa fa-star-half' => 'star-half(award, achievement, rating, score)'),
                array('fa fa-heart-o' => 'Heart Outlined(love, like, favorite)'),
                array('fa fa-sign-out' => 'Sign Out(log out, logout, leave, exit, arrow)'),
                array('fa fa-thumb-tack' => 'Thumb Tack(marker, pin, location, coordinates)'),
                array('fa fa-external-link' => 'External Link(open, new)'),
                array('fa fa-sign-in' => 'Sign In(enter, join, log in, login, sign up, sign in, signin, signup, arrow)'),
                array('fa fa-trophy' => 'trophy(award, achievement, winner, game)'),
                array('fa fa-upload' => 'Upload(import)'),
                array('fa fa-lemon-o' => 'Lemon Outlined(food)'),
                array('fa fa-phone' => 'Phone(call, voice, number, support, earphone)'),
                array('fa fa-square-o' => 'Square Outlined(block, square, box)'),
                array('fa fa-bookmark-o' => 'Bookmark Outlined(save)'),
                array('fa fa-phone-square' => 'Phone Square(call, voice, number, support)'),
                array('fa fa-unlock' => 'unlock(protect, admin, password, lock)'),
                array('fa fa-credit-card' => 'credit-card(money, buy, debit, checkout, purchase, payment)'),
                array('fa fa-rss' => 'rss(blog)(feed)'),
                array('fa fa-hdd-o' => 'HDD(harddrive, hard drive, storage, save)'),
                array('fa fa-bullhorn' => 'bullhorn(announcement, share, broadcast, louder)'),
                array('fa fa-bell' => 'bell(alert, reminder, notification)'),
                array('fa fa-certificate' => 'certificate(badge, star)'),
                array('fa fa-globe' => 'Globe(world, planet, map, place, travel, earth, global, translate, all, language, localize, location, coordinates, country)'),
                array('fa fa-wrench' => 'Wrench(settings, fix, update)'),
                array('fa fa-tasks' => 'Tasks(progress, loading, downloading, downloads, settings)'),
                array('fa fa-filter' => 'Filter(funnel, options)'),
                array('fa fa-briefcase' => 'Briefcase(work, business, office, luggage, bag)'),
                array('fa fa-users' => 'Users(people, profiles, persons)(group)'),
                array('fa fa-cloud' => 'Cloud(save)'),
                array('fa fa-flask' => 'Flask(science, beaker, experimental, labs)'),
                array('fa fa-square' => 'Square(block, box)'),
                array('fa fa-bars' => 'Bars(menu, drag, reorder, settings, list, ul, ol, checklist, todo, list, hamburger)(navicon, reorder)'),
                array('fa fa-magic' => 'magic(wizard, automatic, autocomplete)'),
                array('fa fa-truck' => 'truck(shipping)'),
                array('fa fa-money' => 'Money(cash, money, buy, checkout, purchase, payment)'),
                array('fa fa-sort' => 'Sort(order)(unsorted)'),
                array('fa fa-sort-desc' => 'Sort Descending(dropdown, more, menu, arrow)(sort-down)'),
                array('fa fa-sort-asc' => 'Sort Ascending(arrow)(sort-up)'),
                array('fa fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, notification)'),
                array('fa fa-gavel' => 'Gavel(legal)'),
                array('fa fa-tachometer' => 'Tachometer(dashboard)'),
                array('fa fa-comment-o' => 'comment-o(speech, notification, note, chat, bubble, feedback, message, texting, sms)'),
                array('fa fa-comments-o' => 'comments-o(conversation, notification, notes, message, texting, sms, chat)'),
                array('fa fa-bolt' => 'Lightning Bolt(lightning, weather)(flash)'),
                array('fa fa-sitemap' => 'Sitemap(directory, hierarchy, organization)'),
                array('fa fa-umbrella' => 'Umbrella'),
                array('fa fa-lightbulb-o' => 'Lightbulb Outlined(idea, inspiration)'),
                array('fa fa-exchange' => 'Exchange(transfer, arrows, arrow)'),
                array('fa fa-cloud-download' => 'Cloud Download(import)'),
                array('fa fa-cloud-upload' => 'Cloud Upload(import)'),
                array('fa fa-suitcase' => 'Suitcase(trip, luggage, travel, move, baggage)'),
                array('fa fa-bell-o' => 'Bell Outlined(alert, reminder, notification)'),
                array('fa fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)'),
                array('fa fa-cutlery' => 'Cutlery(food, restaurant, spoon, knife, dinner, eat)'),
                array('fa fa-building-o' => 'Building Outlined(work, business, apartment, office, company)'),
                array('fa fa-fighter-jet' => 'fighter-jet(fly, plane, airplane, quick, fast, travel)'),
                array('fa fa-beer' => 'beer(alcohol, stein, drink, mug, bar, liquor)'),
                array('fa fa-plus-square' => 'Plus Square(add, new, create, expand)'),
                array('fa fa-desktop' => 'Desktop(monitor, screen, desktop, computer, demo, device)'),
                array('fa fa-laptop' => 'Laptop(demo, computer, device)'),
                array('fa fa-tablet' => 'tablet(ipad, device)'),
                array('fa fa-mobile' => 'Mobile Phone(cell phone, cellphone, text, call, iphone, number)(mobile-phone)'),
                array('fa fa-circle-o' => 'Circle Outlined'),
                array('fa fa-quote-left' => 'quote-left'),
                array('fa fa-quote-right' => 'quote-right'),
                array('fa fa-spinner' => 'Spinner(loading, progress)'),
                array('fa fa-circle' => 'Circle(dot, notification)'),
                array('fa fa-reply' => 'Reply(mail-reply)'),
                array('fa fa-folder-o' => 'Folder Outlined'),
                array('fa fa-folder-open-o' => 'Folder Open Outlined'),
                array('fa fa-smile-o' => 'Smile Outlined(face, emoticon, happy, approve, satisfied, rating)'),
                array('fa fa-frown-o' => 'Frown Outlined(face, emoticon, sad, disapprove, rating)'),
                array('fa fa-meh-o' => 'Meh Outlined(face, emoticon, rating, neutral)'),
                array('fa fa-gamepad' => 'Gamepad(controller)'),
                array('fa fa-keyboard-o' => 'Keyboard Outlined(type, input)'),
                array('fa fa-flag-o' => 'Flag Outlined(report, notification)'),
                array('fa fa-flag-checkered' => 'flag-checkered(report, notification, notify)'),
                array('fa fa-terminal' => 'Terminal(command, prompt, code)'),
                array('fa fa-code' => 'Code(html, brackets)'),
                array('fa fa-reply-all' => 'reply-all(mail-reply-all)'),
                array('fa fa-star-half-o' => 'Star Half Outlined(award, achievement, rating, score)(star-half-empty, star-half-full)'),
                array('fa fa-location-arrow' => 'location-arrow(map, coordinates, location, address, place, where)'),
                array('fa fa-crop' => 'crop'),
                array('fa fa-code-fork' => 'code-fork(git, fork, vcs, svn, github, rebase, version, merge)'),
                array('fa fa-question' => 'Question(help, information, unknown, support)'),
                array('fa fa-info' => 'Info(help, information, more, details)'),
                array('fa fa-exclamation' => 'exclamation(warning, error, problem, notification, notify, alert)'),
                array('fa fa-eraser' => 'eraser(remove, delete)'),
                array('fa fa-puzzle-piece' => 'Puzzle Piece(addon, add-on, section)'),
                array('fa fa-microphone' => 'microphone(record, voice, sound)'),
                array('fa fa-microphone-slash' => 'Microphone Slash(record, voice, sound, mute)'),
                array('fa fa-shield' => 'shield(award, achievement, winner)'),
                array('fa fa-calendar-o' => 'calendar-o(date, time, when, event)'),
                array('fa fa-fire-extinguisher' => 'fire-extinguisher'),
                array('fa fa-rocket' => 'rocket(app)'),
                array('fa fa-anchor' => 'Anchor(link)'),
                array('fa fa-unlock-alt' => 'Unlock Alt(protect, admin, password, lock)'),
                array('fa fa-bullseye' => 'Bullseye(target)'),
                array('fa fa-ellipsis-h' => 'Ellipsis Horizontal(dots)'),
                array('fa fa-ellipsis-v' => 'Ellipsis Vertical(dots)'),
                array('fa fa-rss-square' => 'RSS Square(feed, blog)'),
                array('fa fa-ticket' => 'Ticket(movie, pass, support)'),
                array('fa fa-minus-square' => 'Minus Square(hide, minify, delete, remove, trash, hide, collapse)'),
                array('fa fa-minus-square-o' => 'Minus Square Outlined(hide, minify, delete, remove, trash, hide, collapse)'),
                array('fa fa-level-up' => 'Level Up(arrow)'),
                array('fa fa-level-down' => 'Level Down(arrow)'),
                array('fa fa-check-square' => 'Check Square(checkmark, done, todo, agree, accept, confirm, ok)'),
                array('fa fa-pencil-square' => 'Pencil Square(write, edit, update)'),
                array('fa fa-external-link-square' => 'External Link Square(open, new)'),
                array('fa fa-share-square' => 'Share Square(social, send)'),
                array('fa fa-compass' => 'Compass(safari, directory, menu, location)'),
                array('fa fa-caret-square-o-down' => 'Caret Square Outlined Down(more, dropdown, menu)(toggle-down)'),
                array('fa fa-caret-square-o-up' => 'Caret Square Outlined Up(toggle-up)'),
                array('fa fa-caret-square-o-right' => 'Caret Square Outlined Right(next, forward)(toggle-right)'),
                array('fa fa-sort-alpha-asc' => 'Sort Alpha Ascending'),
                array('fa fa-sort-alpha-desc' => 'Sort Alpha Descending'),
                array('fa fa-sort-amount-asc' => 'Sort Amount Ascending'),
                array('fa fa-sort-amount-desc' => 'Sort Amount Descending'),
                array('fa fa-sort-numeric-asc' => 'Sort Numeric Ascending(numbers)'),
                array('fa fa-sort-numeric-desc' => 'Sort Numeric Descending(numbers)'),
                array('fa fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)'),
                array('fa fa-thumbs-down' => 'thumbs-down(dislike, disapprove, disagree, hand)'),
                array('fa fa-female' => 'Female(woman, user, person, profile)'),
                array('fa fa-male' => 'Male(man, user, person, profile)'),
                array('fa fa-sun-o' => 'Sun Outlined(weather, contrast, lighter, brighten, day)'),
                array('fa fa-moon-o' => 'Moon Outlined(night, darker, contrast)'),
                array('fa fa-archive' => 'Archive(box, storage)'),
                array('fa fa-bug' => 'Bug(report, insect)'),
                array('fa fa-caret-square-o-left' => 'Caret Square Outlined Left(previous, back)(toggle-left)'),
                array('fa fa-dot-circle-o' => 'Dot Circle Outlined(target, bullseye, notification)'),
                array('fa fa-wheelchair' => 'Wheelchair(handicap, person, accessibility, accessibile)'),
                array('fa fa-plus-square-o' => 'Plus Square Outlined(add, new, create, expand)'),
                array('fa fa-space-shuttle' => 'Space Shuttle'),
                array('fa fa-envelope-square' => 'Envelope Square'),
                array('fa fa-university' => 'University(institution, bank)'),
                array('fa fa-graduation-cap' => 'Graduation Cap(learning, school, student)(mortar-board)'),
                array('fa fa-language' => 'Language'),
                array('fa fa-fax' => 'Fax'),
                array('fa fa-building' => 'Building(work, business, apartment, office, company)'),
                array('fa fa-child' => 'Child'),
                array('fa fa-paw' => 'Paw(pet)'),
                array('fa fa-spoon' => 'spoon'),
                array('fa fa-cube' => 'Cube'),
                array('fa fa-cubes' => 'Cubes'),
                array('fa fa-recycle' => 'Recycle'),
                array('fa fa-car' => 'Car(vehicle)(automobile)'),
                array('fa fa-taxi' => 'Taxi(vehicle)(cab)'),
                array('fa fa-tree' => 'Tree'),
                array('fa fa-database' => 'Database'),
                array('fa fa-file-pdf-o' => 'PDF File Outlined'),
                array('fa fa-file-word-o' => 'Word File Outlined'),
                array('fa fa-file-excel-o' => 'Excel File Outlined'),
                array('fa fa-file-powerpoint-o' => 'Powerpoint File Outlined'),
                array('fa fa-file-image-o' => 'Image File Outlined(file-photo-o, file-picture-o)'),
                array('fa fa-file-archive-o' => 'Archive File Outlined(file-zip-o)'),
                array('fa fa-file-audio-o' => 'Audio File Outlined(file-sound-o)'),
                array('fa fa-file-video-o' => 'Video File Outlined(file-movie-o)'),
                array('fa fa-file-code-o' => 'Code File Outlined'),
                array('fa fa-life-ring' => 'Life Ring(life-bouy, life-buoy, life-saver, support)'),
                array('fa fa-circle-o-notch' => 'Circle Outlined Notched'),
                array('fa fa-paper-plane' => 'Paper Plane(send)'),
                array('fa fa-paper-plane-o' => 'Paper Plane Outlined(send-o)'),
                array('fa fa-history' => 'History'),
                array('fa fa-circle-thin' => 'Circle Outlined Thin'),
                array('fa fa-sliders' => 'Sliders(settings)'),
                array('fa fa-share-alt' => 'Share Alt'),
                array('fa fa-share-alt-square' => 'Share Alt Square'),
                array('fa fa-bomb' => 'Bomb'),
                array('fa fa-futbol-o' => 'Futbol Outlined(soccer-ball-o)'),
                array('fa fa-tty' => 'TTY'),
                array('fa fa-binoculars' => 'Binoculars'),
                array('fa fa-plug' => 'Plug(power, connect)'),
                array('fa fa-newspaper-o' => 'Newspaper Outlined(press)'),
                array('fa fa-wifi' => 'WiFi'),
                array('fa fa-calculator' => 'Calculator'),
                array('fa fa-bell-slash' => 'Bell Slash'),
                array('fa fa-bell-slash-o' => 'Bell Slash Outlined'),
                array('fa fa-trash' => 'Trash(garbage, delete, remove, hide)'),
                array('fa fa-copyright' => 'Copyright'),
                array('fa fa-at' => 'At'),
                array('fa fa-eyedropper' => 'Eyedropper'),
                array('fa fa-paint-brush' => 'Paint Brush'),
                array('fa fa-birthday-cake' => 'Birthday Cake'),
                array('fa fa-area-chart' => 'Area Chart(graph, analytics)'),
                array('fa fa-pie-chart' => 'Pie Chart(graph, analytics)'),
                array('fa fa-line-chart' => 'Line Chart(graph, analytics)'),
                array('fa fa-toggle-off' => 'Toggle Off'),
                array('fa fa-toggle-on' => 'Toggle On'),
                array('fa fa-bicycle' => 'Bicycle(vehicle, bike)'),
                array('fa fa-bus' => 'Bus(vehicle)'),
                array('fa fa-cc' => 'Closed Captions'),
                array('fa fa-cart-plus' => 'Add to Shopping Cart(add, shopping)'),
                array('fa fa-cart-arrow-down' => 'Shopping Cart Arrow Down(shopping)'),
                array('fa fa-diamond' => 'Diamond(gem, gemstone)'),
                array('fa fa-ship' => 'Ship(boat, sea)'),
                array('fa fa-user-secret' => 'User Secret(whisper, spy, incognito, privacy)'),
                array('fa fa-motorcycle' => 'Motorcycle(vehicle, bike)'),
                array('fa fa-street-view' => 'Street View(map)'),
                array('fa fa-heartbeat' => 'Heartbeat(ekg)'),
                array('fa fa-server' => 'Server'),
                array('fa fa-user-plus' => 'Add User(sign up, signup)'),
                array('fa fa-user-times' => 'Remove User'),
                array('fa fa-bed' => 'Bed(travel)(hotel)'),
                array('fa fa-battery-full' => 'Battery Full(power)(battery-4)'),
                array('fa fa-battery-three-quarters' => 'Battery 3/4 Full(power)(battery-3)'),
                array('fa fa-battery-half' => 'Battery 1/2 Full(power)(battery-2)'),
                array('fa fa-battery-quarter' => 'Battery 1/4 Full(power)(battery-1)'),
                array('fa fa-battery-empty' => 'Battery Empty(power)(battery-0)'),
                array('fa fa-mouse-pointer' => 'Mouse Pointer'),
                array('fa fa-i-cursor' => 'I Beam Cursor'),
                array('fa fa-object-group' => 'Object Group'),
                array('fa fa-object-ungroup' => 'Object Ungroup'),
                array('fa fa-sticky-note' => 'Sticky Note'),
                array('fa fa-sticky-note-o' => 'Sticky Note Outlined'),
                array('fa fa-clone' => 'Clone(copy)'),
                array('fa fa-balance-scale' => 'Balance Scale'),
                array('fa fa-hourglass-o' => 'Hourglass Outlined'),
                array('fa fa-hourglass-start' => 'Hourglass Start(hourglass-1)'),
                array('fa fa-hourglass-half' => 'Hourglass Half(hourglass-2)'),
                array('fa fa-hourglass-end' => 'Hourglass End(hourglass-3)'),
                array('fa fa-hourglass' => 'Hourglass'),
                array('fa fa-hand-rock-o' => 'Rock (Hand)(hand-grab-o)'),
                array('fa fa-hand-paper-o' => 'Paper (Hand)(stop)(hand-stop-o)'),
                array('fa fa-hand-scissors-o' => 'Scissors (Hand)'),
                array('fa fa-hand-lizard-o' => 'Lizard (Hand)'),
                array('fa fa-hand-spock-o' => 'Spock (Hand)'),
                array('fa fa-hand-pointer-o' => 'Hand Pointer'),
                array('fa fa-hand-peace-o' => 'Hand Peace'),
                array('fa fa-trademark' => 'Trademark'),
                array('fa fa-registered' => 'Registered Trademark'),
                array('fa fa-creative-commons' => 'Creative Commons'),
                array('fa fa-television' => 'Television(display, computer, monitor)(tv)'),
                array('fa fa-calendar-plus-o' => 'Calendar Plus Outlined'),
                array('fa fa-calendar-minus-o' => 'Calendar Minus Outlined'),
                array('fa fa-calendar-times-o' => 'Calendar Times Outlined'),
                array('fa fa-calendar-check-o' => 'Calendar Check Outlined(ok)'),
                array('fa fa-industry' => 'Industry(factory)'),
                array('fa fa-map-pin' => 'Map Pin'),
                array('fa fa-map-signs' => 'Map Signs'),
                array('fa fa-map-o' => 'Map Outline'),
                array('fa fa-map' => 'Map'),
                array('fa fa-commenting' => 'Commenting(message)'),
                array('fa fa-commenting-o' => 'Commenting Outlined(message)'),
                array('fa fa-credit-card-alt' => 'Credit Card(money, buy, debit, checkout, purchase, payment, credit card)'),
                array('fa fa-shopping-bag' => 'Shopping Bag'),
                array('fa fa-shopping-basket' => 'Shopping Basket'),
                array('fa fa-hashtag' => 'Hashtag'),
                array('fa fa-bluetooth' => 'Bluetooth'),
                array('fa fa-bluetooth-b' => 'Bluetooth'),
                array('fa fa-percent' => 'Percent'),
                array('fa fa-universal-access' => 'Universal Access'),
                array('fa fa-wheelchair-alt' => 'Wheelchair Alt'),
                array('fa fa-question-circle-o' => 'Question Circle Outlined'),
                array('fa fa-blind' => 'Blind'),
                array('fa fa-audio-description' => 'Audio Description'),
                array('fa fa-volume-control-phone' => 'Volume Control Phone'),
                array('fa fa-braille' => 'Braille'),
                array('fa fa-assistive-listening-systems' => 'Assistive Listening Systems'),
                array('fa fa-american-sign-language-interpreting' => 'American Sign Language Interpreting(asl-interpreting)'),
                array('fa fa-deaf' => 'Deaf(deafness, hard-of-hearing)'),
                array('fa fa-sign-language' => 'Sign Language(signing)'),
                array('fa fa-low-vision' => 'Low Vision'),
            ),
            'Medical Icons' => array(
                array('fa fa-heart' => 'Heart(love, like, favorite)'),
                array('fa fa-heart-o' => 'Heart Outlined(love, like, favorite)'),
                array('fa fa-user-md' => 'user-md(doctor, profile, medical, nurse)'),
                array('fa fa-stethoscope' => 'Stethoscope'),
                array('fa fa-hospital-o' => 'hospital Outlined(building)'),
                array('fa fa-ambulance' => 'ambulance(vehicle, support, help)'),
                array('fa fa-medkit' => 'medkit(first aid, firstaid, help, support, health)'),
                array('fa fa-h-square' => 'H Square(hospital, hotel)'),
                array('fa fa-plus-square' => 'Plus Square(add, new, create, expand)'),
                array('fa fa-wheelchair' => 'Wheelchair(handicap, person, accessibility, accessibile)'),
                array('fa fa-heartbeat' => 'Heartbeat(ekg)'),
            ),
            'Text Editor Icons' => array(
                array('fa fa-th-large' => 'th-large(blocks, squares, boxes, grid)'),
                array('fa fa-th' => 'th(blocks, squares, boxes, grid)'),
                array('fa fa-th-list' => 'th-list(ul, ol, checklist, finished, completed, done, todo)'),
                array('fa fa-file-o' => 'File Outlined(new, page, pdf, document)'),
                array('fa fa-repeat' => 'Repeat(redo, forward)(rotate-right)'),
                array('fa fa-list-alt' => 'list-alt(ul, ol, checklist, finished, completed, done, todo)'),
                array('fa fa-font' => 'font(text)'),
                array('fa fa-bold' => 'bold'),
                array('fa fa-italic' => 'italic(italics)'),
                array('fa fa-text-height' => 'text-height'),
                array('fa fa-text-width' => 'text-width'),
                array('fa fa-align-left' => 'align-left(text)'),
                array('fa fa-align-center' => 'align-center(middle, text)'),
                array('fa fa-align-right' => 'align-right(text)'),
                array('fa fa-align-justify' => 'align-justify(text)'),
                array('fa fa-list' => 'list(ul, ol, checklist, finished, completed, done, todo)'),
                array('fa fa-outdent' => 'Outdent(dedent)'),
                array('fa fa-indent' => 'Indent'),
                array('fa fa-link' => 'Link(chain)(chain)'),
                array('fa fa-scissors' => 'Scissors(cut)'),
                array('fa fa-files-o' => 'Files Outlined(duplicate, clone, copy)(copy)'),
                array('fa fa-paperclip' => 'Paperclip(attachment)'),
                array('fa fa-floppy-o' => 'Floppy Outlined(save)'),
                array('fa fa-list-ul' => 'list-ul(ul, ol, checklist, todo, list)'),
                array('fa fa-list-ol' => 'list-ol(ul, ol, checklist, list, todo, list, numbers)'),
                array('fa fa-strikethrough' => 'Strikethrough'),
                array('fa fa-underline' => 'Underline'),
                array('fa fa-table' => 'table(data, excel, spreadsheet)'),
                array('fa fa-columns' => 'Columns(split, panes)'),
                array('fa fa-undo' => 'Undo(back)(rotate-left)'),
                array('fa fa-clipboard' => 'Clipboard(copy)(paste)'),
                array('fa fa-file-text-o' => 'File Text Outlined(new, page, pdf, document)'),
                array('fa fa-chain-broken' => 'Chain Broken(remove)(unlink)'),
                array('fa fa-superscript' => 'superscript(exponential)'),
                array('fa fa-subscript' => 'subscript'),
                array('fa fa-eraser' => 'eraser(remove, delete)'),
                array('fa fa-file' => 'File(new, page, pdf, document)'),
                array('fa fa-file-text' => 'File Text(new, page, pdf, document)'),
                array('fa fa-header' => 'header(heading)'),
                array('fa fa-paragraph' => 'paragraph'),
            ),
            'Spinner Icons' => array(
                array('fa fa-cog' => 'cog(settings)(gear)'),
                array('fa fa-refresh' => 'refresh(reload, sync)'),
                array('fa fa-spinner' => 'Spinner(loading, progress)'),
                array('fa fa-circle-o-notch' => 'Circle Outlined Notched'),
            ),
            'File Type Icons' => array(
                array('fa fa-file-o' => 'File Outlined(new, page, pdf, document)'),
                array('fa fa-file-text-o' => 'File Text Outlined(new, page, pdf, document)'),
                array('fa fa-file' => 'File(new, page, pdf, document)'),
                array('fa fa-file-text' => 'File Text(new, page, pdf, document)'),
                array('fa fa-file-pdf-o' => 'PDF File Outlined'),
                array('fa fa-file-word-o' => 'Word File Outlined'),
                array('fa fa-file-excel-o' => 'Excel File Outlined'),
                array('fa fa-file-powerpoint-o' => 'Powerpoint File Outlined'),
                array('fa fa-file-image-o' => 'Image File Outlined(file-photo-o, file-picture-o)'),
                array('fa fa-file-archive-o' => 'Archive File Outlined(file-zip-o)'),
                array('fa fa-file-audio-o' => 'Audio File Outlined(file-sound-o)'),
                array('fa fa-file-video-o' => 'Video File Outlined(file-movie-o)'),
                array('fa fa-file-code-o' => 'Code File Outlined'),
            ),
            'Directional Icons' => array(
                array('fa fa-arrow-circle-o-down' => 'Arrow Circle Outlined Down(download)'),
                array('fa fa-arrow-circle-o-up' => 'Arrow Circle Outlined Up'),
                array('fa fa-arrows' => 'Arrows(move, reorder, resize)'),
                array('fa fa-chevron-left' => 'chevron-left(bracket, previous, back)'),
                array('fa fa-chevron-right' => 'chevron-right(bracket, next, forward)'),
                array('fa fa-arrow-left' => 'arrow-left(previous, back)'),
                array('fa fa-arrow-right' => 'arrow-right(next, forward)'),
                array('fa fa-arrow-up' => 'arrow-up'),
                array('fa fa-arrow-down' => 'arrow-down(download)'),
                array('fa fa-chevron-up' => 'chevron-up'),
                array('fa fa-chevron-down' => 'chevron-down'),
                array('fa fa-arrows-v' => 'Arrows Vertical(resize)'),
                array('fa fa-arrows-h' => 'Arrows Horizontal(resize)'),
                array('fa fa-hand-o-right' => 'Hand Outlined Right(point, right, next, forward, finger)'),
                array('fa fa-hand-o-left' => 'Hand Outlined Left(point, left, previous, back, finger)'),
                array('fa fa-hand-o-up' => 'Hand Outlined Up(point, finger)'),
                array('fa fa-hand-o-down' => 'Hand Outlined Down(point, finger)'),
                array('fa fa-arrow-circle-left' => 'Arrow Circle Left(previous, back)'),
                array('fa fa-arrow-circle-right' => 'Arrow Circle Right(next, forward)'),
                array('fa fa-arrow-circle-up' => 'Arrow Circle Up'),
                array('fa fa-arrow-circle-down' => 'Arrow Circle Down(download)'),
                array('fa fa-arrows-alt' => 'Arrows Alt(expand, enlarge, fullscreen, bigger, move, reorder, resize, arrow)'),
                array('fa fa-caret-down' => 'Caret Down(more, dropdown, menu, triangle down, arrow)'),
                array('fa fa-caret-up' => 'Caret Up(triangle up, arrow)'),
                array('fa fa-caret-left' => 'Caret Left(previous, back, triangle left, arrow)'),
                array('fa fa-caret-right' => 'Caret Right(next, forward, triangle right, arrow)'),
                array('fa fa-exchange' => 'Exchange(transfer, arrows, arrow)'),
                array('fa fa-angle-double-left' => 'Angle Double Left(laquo, quote, previous, back, arrows)'),
                array('fa fa-angle-double-right' => 'Angle Double Right(raquo, quote, next, forward, arrows)'),
                array('fa fa-angle-double-up' => 'Angle Double Up(arrows)'),
                array('fa fa-angle-double-down' => 'Angle Double Down(arrows)'),
                array('fa fa-angle-left' => 'angle-left(previous, back, arrow)'),
                array('fa fa-angle-right' => 'angle-right(next, forward, arrow)'),
                array('fa fa-angle-up' => 'angle-up(arrow)'),
                array('fa fa-angle-down' => 'angle-down(arrow)'),
                array('fa fa-chevron-circle-left' => 'Chevron Circle Left(previous, back, arrow)'),
                array('fa fa-chevron-circle-right' => 'Chevron Circle Right(next, forward, arrow)'),
                array('fa fa-chevron-circle-up' => 'Chevron Circle Up(arrow)'),
                array('fa fa-chevron-circle-down' => 'Chevron Circle Down(more, dropdown, menu, arrow)'),
                array('fa fa-caret-square-o-down' => 'Caret Square Outlined Down(more, dropdown, menu)(toggle-down)'),
                array('fa fa-caret-square-o-up' => 'Caret Square Outlined Up(toggle-up)'),
                array('fa fa-caret-square-o-right' => 'Caret Square Outlined Right(next, forward)(toggle-right)'),
                array('fa fa-long-arrow-down' => 'Long Arrow Down'),
                array('fa fa-long-arrow-up' => 'Long Arrow Up'),
                array('fa fa-long-arrow-left' => 'Long Arrow Left(previous, back)'),
                array('fa fa-long-arrow-right' => 'Long Arrow Right'),
                array('fa fa-arrow-circle-o-right' => 'Arrow Circle Outlined Right(next, forward)'),
                array('fa fa-arrow-circle-o-left' => 'Arrow Circle Outlined Left(previous, back)'),
                array('fa fa-caret-square-o-left' => 'Caret Square Outlined Left(previous, back)(toggle-left)'),
            ),
            'Video Player Icons' => array(
                array('fa fa-play-circle-o' => 'Play Circle Outlined'),
                array('fa fa-step-backward' => 'step-backward(rewind, previous, beginning, start, first)'),
                array('fa fa-fast-backward' => 'fast-backward(rewind, previous, beginning, start, first)'),
                array('fa fa-backward' => 'backward(rewind, previous)'),
                array('fa fa-play' => 'play(start, playing, music, sound)'),
                array('fa fa-pause' => 'pause(wait)'),
                array('fa fa-stop' => 'stop(block, box, square)'),
                array('fa fa-forward' => 'forward(forward, next)'),
                array('fa fa-fast-forward' => 'fast-forward(next, end, last)'),
                array('fa fa-step-forward' => 'step-forward(next, end, last)'),
                array('fa fa-eject' => 'eject'),
                array('fa fa-expand' => 'Expand(enlarge, bigger, resize)'),
                array('fa fa-compress' => 'Compress(collapse, combine, contract, merge, smaller)'),
                array('fa fa-random' => 'random(sort, shuffle)'),
                array('fa fa-arrows-alt' => 'Arrows Alt(expand, enlarge, fullscreen, bigger, move, reorder, resize, arrow)'),
                array('fa fa-play-circle' => 'Play Circle(start, playing)'),
                array('fa fa-youtube-play' => 'YouTube Play(start, playing)'),
                array('fa fa-pause-circle' => 'Pause Circle'),
                array('fa fa-pause-circle-o' => 'Pause Circle Outlined'),
                array('fa fa-stop-circle' => 'Stop Circle'),
                array('fa fa-stop-circle-o' => 'Stop Circle Outlined'),
            ),
            'Form Control Icons' => array(
                array('fa fa-check-square-o' => 'Check Square Outlined(todo, done, agree, accept, confirm, ok)'),
                array('fa fa-square-o' => 'Square Outlined(block, square, box)'),
                array('fa fa-square' => 'Square(block, box)'),
                array('fa fa-plus-square' => 'Plus Square(add, new, create, expand)'),
                array('fa fa-circle-o' => 'Circle Outlined'),
                array('fa fa-circle' => 'Circle(dot, notification)'),
                array('fa fa-minus-square' => 'Minus Square(hide, minify, delete, remove, trash, hide, collapse)'),
                array('fa fa-minus-square-o' => 'Minus Square Outlined(hide, minify, delete, remove, trash, hide, collapse)'),
                array('fa fa-check-square' => 'Check Square(checkmark, done, todo, agree, accept, confirm, ok)'),
                array('fa fa-dot-circle-o' => 'Dot Circle Outlined(target, bullseye, notification)'),
                array('fa fa-plus-square-o' => 'Plus Square Outlined(add, new, create, expand)'),
            ),
            'Transportation Icons' => array(
                array('fa fa-plane' => 'plane(travel, trip, location, destination, airplane, fly, mode)'),
                array('fa fa-truck' => 'truck(shipping)'),
                array('fa fa-ambulance' => 'ambulance(vehicle, support, help)'),
                array('fa fa-fighter-jet' => 'fighter-jet(fly, plane, airplane, quick, fast, travel)'),
                array('fa fa-rocket' => 'rocket(app)'),
                array('fa fa-wheelchair' => 'Wheelchair(handicap, person, accessibility, accessibile)'),
                array('fa fa-space-shuttle' => 'Space Shuttle'),
                array('fa fa-car' => 'Car(vehicle)(automobile)'),
                array('fa fa-taxi' => 'Taxi(vehicle)(cab)'),
                array('fa fa-bicycle' => 'Bicycle(vehicle, bike)'),
                array('fa fa-bus' => 'Bus(vehicle)'),
                array('fa fa-ship' => 'Ship(boat, sea)'),
                array('fa fa-motorcycle' => 'Motorcycle(vehicle, bike)'),
                array('fa fa-train' => 'Train'),
                array('fa fa-subway' => 'Subway'),
            ),
            'Chart Icons' => array(
                array('fa fa-bar-chart' => 'Bar Chart(graph, analytics)(bar-chart-o)'),
                array('fa fa-area-chart' => 'Area Chart(graph, analytics)'),
                array('fa fa-pie-chart' => 'Pie Chart(graph, analytics)'),
                array('fa fa-line-chart' => 'Line Chart(graph, analytics)'),
            ),
            'Brand Icons' => array(
                array('fa fa-twitter-square' => 'Twitter Square(tweet, social network)'),
                array('fa fa-facebook-square' => 'Facebook Square(social network)'),
                array('fa fa-linkedin-square' => 'LinkedIn Square'),
                array('fa fa-github-square' => 'GitHub Square(octocat)'),
                array('fa fa-twitter' => 'Twitter(tweet, social network)'),
                array('fa fa-facebook' => 'Facebook(social network)(facebook-f)'),
                array('fa fa-github' => 'GitHub(octocat)'),
                array('fa fa-pinterest' => 'Pinterest'),
                array('fa fa-pinterest-square' => 'Pinterest Square'),
                array('fa fa-google-plus-square' => 'Google Plus Square(social network)'),
                array('fa fa-google-plus' => 'Google Plus(social network)'),
                array('fa fa-linkedin' => 'LinkedIn'),
                array('fa fa-github-alt' => 'GitHub Alt(octocat)'),
                array('fa fa-maxcdn' => 'MaxCDN'),
                array('fa fa-html5' => 'HTML 5 Logo'),
                array('fa fa-css3' => 'CSS 3 Logo(code)'),
                array('fa fa-btc' => 'Bitcoin (BTC)(bitcoin)'),
                array('fa fa-youtube-square' => 'YouTube Square(video, film)'),
                array('fa fa-youtube' => 'YouTube(video, film)'),
                array('fa fa-xing' => 'Xing'),
                array('fa fa-xing-square' => 'Xing Square'),
                array('fa fa-youtube-play' => 'YouTube Play(start, playing)'),
                array('fa fa-dropbox' => 'Dropbox'),
                array('fa fa-stack-overflow' => 'Stack Overflow'),
                array('fa fa-instagram' => 'Instagram'),
                array('fa fa-flickr' => 'Flickr'),
                array('fa fa-adn' => 'App.net'),
                array('fa fa-bitbucket' => 'Bitbucket(git)'),
                array('fa fa-bitbucket-square' => 'Bitbucket Square(git)'),
                array('fa fa-tumblr' => 'Tumblr'),
                array('fa fa-tumblr-square' => 'Tumblr Square'),
                array('fa fa-apple' => 'Apple(osx, food)'),
                array('fa fa-windows' => 'Windows(microsoft)'),
                array('fa fa-android' => 'Android(robot)'),
                array('fa fa-linux' => 'Linux(tux)'),
                array('fa fa-dribbble' => 'Dribbble'),
                array('fa fa-skype' => 'Skype'),
                array('fa fa-foursquare' => 'Foursquare'),
                array('fa fa-trello' => 'Trello'),
                array('fa fa-gratipay' => 'Gratipay (Gittip)(heart, like, favorite, love)(gittip)'),
                array('fa fa-vk' => 'VK'),
                array('fa fa-weibo' => 'Weibo'),
                array('fa fa-renren' => 'Renren'),
                array('fa fa-pagelines' => 'Pagelines(leaf, leaves, tree, plant, eco, nature)'),
                array('fa fa-stack-exchange' => 'Stack Exchange'),
                array('fa fa-vimeo-square' => 'Vimeo Square'),
                array('fa fa-slack' => 'Slack Logo(hashtag, anchor, hash)'),
                array('fa fa-wordpress' => 'WordPress Logo'),
                array('fa fa-openid' => 'OpenID'),
                array('fa fa-yahoo' => 'Yahoo Logo'),
                array('fa fa-google' => 'Google Logo'),
                array('fa fa-reddit' => 'reddit Logo'),
                array('fa fa-reddit-square' => 'reddit Square'),
                array('fa fa-stumbleupon-circle' => 'StumbleUpon Circle'),
                array('fa fa-stumbleupon' => 'StumbleUpon Logo'),
                array('fa fa-delicious' => 'Delicious Logo'),
                array('fa fa-digg' => 'Digg Logo'),
                array('fa fa-pied-piper-pp' => 'Pied Piper PP Logo (Old)'),
                array('fa fa-pied-piper-alt' => 'Pied Piper Alternate Logo'),
                array('fa fa-drupal' => 'Drupal Logo'),
                array('fa fa-joomla' => 'Joomla Logo'),
                array('fa fa-behance' => 'Behance'),
                array('fa fa-behance-square' => 'Behance Square'),
                array('fa fa-steam' => 'Steam'),
                array('fa fa-steam-square' => 'Steam Square'),
                array('fa fa-spotify' => 'Spotify'),
                array('fa fa-deviantart' => 'deviantART'),
                array('fa fa-soundcloud' => 'SoundCloud'),
                array('fa fa-vine' => 'Vine'),
                array('fa fa-codepen' => 'Codepen'),
                array('fa fa-jsfiddle' => 'jsFiddle'),
                array('fa fa-rebel' => 'Rebel Alliance(ra, resistance)'),
                array('fa fa-empire' => 'Galactic Empire(ge)'),
                array('fa fa-git-square' => 'Git Square'),
                array('fa fa-git' => 'Git'),
                array('fa fa-hacker-news' => 'Hacker News(y-combinator-square, yc-square)'),
                array('fa fa-tencent-weibo' => 'Tencent Weibo'),
                array('fa fa-qq' => 'QQ'),
                array('fa fa-weixin' => 'Weixin (WeChat)(wechat)'),
                array('fa fa-share-alt' => 'Share Alt'),
                array('fa fa-share-alt-square' => 'Share Alt Square'),
                array('fa fa-slideshare' => 'Slideshare'),
                array('fa fa-twitch' => 'Twitch'),
                array('fa fa-yelp' => 'Yelp'),
                array('fa fa-paypal' => 'Paypal'),
                array('fa fa-google-wallet' => 'Google Wallet'),
                array('fa fa-cc-visa' => 'Visa Credit Card'),
                array('fa fa-cc-mastercard' => 'MasterCard Credit Card'),
                array('fa fa-cc-discover' => 'Discover Credit Card'),
                array('fa fa-cc-amex' => 'American Express Credit Card(amex)'),
                array('fa fa-cc-paypal' => 'Paypal Credit Card'),
                array('fa fa-cc-stripe' => 'Stripe Credit Card'),
                array('fa fa-lastfm' => 'last.fm'),
                array('fa fa-lastfm-square' => 'last.fm Square'),
                array('fa fa-ioxhost' => 'ioxhost'),
                array('fa fa-angellist' => 'AngelList'),
                array('fa fa-meanpath' => 'meanpath'),
                array('fa fa-buysellads' => 'BuySellAds'),
                array('fa fa-connectdevelop' => 'Connect Develop'),
                array('fa fa-dashcube' => 'DashCube'),
                array('fa fa-forumbee' => 'Forumbee'),
                array('fa fa-leanpub' => 'Leanpub'),
                array('fa fa-sellsy' => 'Sellsy'),
                array('fa fa-shirtsinbulk' => 'Shirts in Bulk'),
                array('fa fa-simplybuilt' => 'SimplyBuilt'),
                array('fa fa-skyatlas' => 'skyatlas'),
                array('fa fa-facebook-official' => 'Facebook Official'),
                array('fa fa-pinterest-p' => 'Pinterest P'),
                array('fa fa-whatsapp' => 'What\'s App'),
                array('fa fa-viacoin' => 'Viacoin'),
                array('fa fa-medium' => 'Medium'),
                array('fa fa-y-combinator' => 'Y Combinator(yc)'),
                array('fa fa-optin-monster' => 'Optin Monster'),
                array('fa fa-opencart' => 'OpenCart'),
                array('fa fa-expeditedssl' => 'ExpeditedSSL'),
                array('fa fa-cc-jcb' => 'JCB Credit Card'),
                array('fa fa-cc-diners-club' => 'Diner\'s Club Credit Card'),
                array('fa fa-gg' => 'GG Currency'),
                array('fa fa-gg-circle' => 'GG Currency Circle'),
                array('fa fa-tripadvisor' => 'TripAdvisor'),
                array('fa fa-odnoklassniki' => 'Odnoklassniki'),
                array('fa fa-odnoklassniki-square' => 'Odnoklassniki Square'),
                array('fa fa-get-pocket' => 'Get Pocket'),
                array('fa fa-wikipedia-w' => 'Wikipedia W'),
                array('fa fa-safari' => 'Safari(browser)'),
                array('fa fa-chrome' => 'Chrome(browser)'),
                array('fa fa-firefox' => 'Firefox(browser)'),
                array('fa fa-opera' => 'Opera'),
                array('fa fa-internet-explorer' => 'Internet-explorer(browser, ie)'),
                array('fa fa-contao' => 'Contao'),
                array('fa fa-500px' => '500px'),
                array('fa fa-amazon' => 'Amazon'),
                array('fa fa-houzz' => 'Houzz'),
                array('fa fa-vimeo' => 'Vimeo'),
                array('fa fa-black-tie' => 'Font Awesome Black Tie'),
                array('fa fa-fonticons' => 'Fonticons'),
                array('fa fa-reddit-alien' => 'reddit Alien'),
                array('fa fa-edge' => 'Edge Browser(browser, ie)'),
                array('fa fa-codiepie' => 'Codie Pie'),
                array('fa fa-modx' => 'MODX'),
                array('fa fa-fort-awesome' => 'Fort Awesome'),
                array('fa fa-usb' => 'USB'),
                array('fa fa-product-hunt' => 'Product Hunt'),
                array('fa fa-mixcloud' => 'Mixcloud'),
                array('fa fa-scribd' => 'Scribd'),
                array('fa fa-bluetooth' => 'Bluetooth'),
                array('fa fa-bluetooth-b' => 'Bluetooth'),
                array('fa fa-gitlab' => 'GitLab'),
                array('fa fa-wpbeginner' => 'WPBeginner'),
                array('fa fa-wpforms' => 'WPForms'),
                array('fa fa-envira' => 'Envira Gallery(leaf)'),
                array('fa fa-glide' => 'Glide'),
                array('fa fa-glide-g' => 'Glide G'),
                array('fa fa-viadeo' => 'Viadeo'),
                array('fa fa-viadeo-square' => 'Viadeo Square'),
                array('fa fa-snapchat' => 'Snapchat'),
                array('fa fa-snapchat-ghost' => 'Snapchat Ghost'),
                array('fa fa-snapchat-square' => 'Snapchat Square'),
                array('fa fa-pied-piper' => 'Pied Piper Logo'),
                array('fa fa-first-order' => 'First Order'),
                array('fa fa-yoast' => 'Yoast'),
                array('fa fa-themeisle' => 'ThemeIsle'),
                array('fa fa-google-plus-official' => 'Google Plus Official(google-plus-circle)'),
                array('fa fa-font-awesome' => 'Font Awesome(fa)'),
            ),
            'Hand Icons' => array(
                array('fa fa-thumbs-o-up' => 'Thumbs Up Outlined(like, approve, favorite, agree, hand)'),
                array('fa fa-thumbs-o-down' => 'Thumbs Down Outlined(dislike, disapprove, disagree, hand)'),
                array('fa fa-hand-o-right' => 'Hand Outlined Right(point, right, next, forward, finger)'),
                array('fa fa-hand-o-left' => 'Hand Outlined Left(point, left, previous, back, finger)'),
                array('fa fa-hand-o-up' => 'Hand Outlined Up(point, finger)'),
                array('fa fa-hand-o-down' => 'Hand Outlined Down(point, finger)'),
                array('fa fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)'),
                array('fa fa-thumbs-down' => 'thumbs-down(dislike, disapprove, disagree, hand)'),
                array('fa fa-hand-rock-o' => 'Rock (Hand)(hand-grab-o)'),
                array('fa fa-hand-paper-o' => 'Paper (Hand)(stop)(hand-stop-o)'),
                array('fa fa-hand-scissors-o' => 'Scissors (Hand)'),
                array('fa fa-hand-lizard-o' => 'Lizard (Hand)'),
                array('fa fa-hand-spock-o' => 'Spock (Hand)'),
                array('fa fa-hand-pointer-o' => 'Hand Pointer'),
                array('fa fa-hand-peace-o' => 'Hand Peace'),
            ),
            'Payment Icons' => array(
                array('fa fa-credit-card' => 'credit-card(money, buy, debit, checkout, purchase, payment)'),
                array('fa fa-paypal' => 'Paypal'),
                array('fa fa-google-wallet' => 'Google Wallet'),
                array('fa fa-cc-visa' => 'Visa Credit Card'),
                array('fa fa-cc-mastercard' => 'MasterCard Credit Card'),
                array('fa fa-cc-discover' => 'Discover Credit Card'),
                array('fa fa-cc-amex' => 'American Express Credit Card(amex)'),
                array('fa fa-cc-paypal' => 'Paypal Credit Card'),
                array('fa fa-cc-stripe' => 'Stripe Credit Card'),
                array('fa fa-cc-jcb' => 'JCB Credit Card'),
                array('fa fa-cc-diners-club' => 'Diner\'s Club Credit Card'),
                array('fa fa-credit-card-alt' => 'Credit Card(money, buy, debit, checkout, purchase, payment, credit card)'),
            ),
            'Currency Icons' => array(
                array('fa fa-money' => 'Money(cash, money, buy, checkout, purchase, payment)'),
                array('fa fa-eur' => 'Euro (EUR)(euro)'),
                array('fa fa-gbp' => 'GBP'),
                array('fa fa-usd' => 'US Dollar(dollar)'),
                array('fa fa-inr' => 'Indian Rupee (INR)(rupee)'),
                array('fa fa-jpy' => 'Japanese Yen (JPY)(cny, rmb, yen)'),
                array('fa fa-rub' => 'Russian Ruble (RUB)(ruble, rouble)'),
                array('fa fa-krw' => 'Korean Won (KRW)(won)'),
                array('fa fa-btc' => 'Bitcoin (BTC)(bitcoin)'),
                array('fa fa-try' => 'Turkish Lira (TRY)(turkish-lira)'),
                array('fa fa-ils' => 'Shekel (ILS)(shekel, sheqel)'),
                array('fa fa-gg' => 'GG Currency'),
                array('fa fa-gg-circle' => 'GG Currency Circle'),
            ),
            'Accessibility Icons' => array(
                array('fa fa-wheelchair' => 'Wheelchair(handicap, person, accessibility, accessibile)'),
                array('fa fa-tty' => 'TTY'),
                array('fa fa-cc' => 'Closed Captions'),
                array('fa fa-universal-access' => 'Universal Access'),
                array('fa fa-wheelchair-alt' => 'Wheelchair Alt'),
                array('fa fa-question-circle-o' => 'Question Circle Outlined'),
                array('fa fa-blind' => 'Blind'),
                array('fa fa-audio-description' => 'Audio Description'),
                array('fa fa-volume-control-phone' => 'Volume Control Phone'),
                array('fa fa-braille' => 'Braille'),
                array('fa fa-assistive-listening-systems' => 'Assistive Listening Systems'),
                array('fa fa-american-sign-language-interpreting' => 'American Sign Language Interpreting(asl-interpreting)'),
                array('fa fa-deaf' => 'Deaf(deafness, hard-of-hearing)'),
                array('fa fa-sign-language' => 'Sign Language(signing)'),
                array('fa fa-low-vision' => 'Low Vision'),
            ),
            'Gender Icons' => array(
                array('fa fa-venus' => 'Venus(female)'),
                array('fa fa-mars' => 'Mars(male)'),
                array('fa fa-mercury' => 'Mercury(transgender)'),
                array('fa fa-transgender' => 'Transgender(intersex)'),
                array('fa fa-transgender-alt' => 'Transgender Alt'),
                array('fa fa-venus-double' => 'Venus Double'),
                array('fa fa-mars-double' => 'Mars Double'),
                array('fa fa-venus-mars' => 'Venus Mars'),
                array('fa fa-mars-stroke' => 'Mars Stroke'),
                array('fa fa-mars-stroke-v' => 'Mars Stroke Vertical'),
                array('fa fa-mars-stroke-h' => 'Mars Stroke Horizontal'),
                array('fa fa-neuter' => 'Neuter'),
                array('fa fa-genderless' => 'Genderless'),
            ),
        );

        $icons = apply_filters("evolt_mega_menu/get_icons", $icons);

        return $icons;
    }
}