<?php
class Button {

    public $classList = "";
    public $attributes = array(
        'url' => '',
        'target' => '_self',
        'title' => ''
    );
    public $type = "primary";

    function __construct($btn = array(
        'url' => '',
        'target' => '_self',
        'title' => ''
    ), $classes = "", $type = 'primary') {

        if (is_array($btn)) {
            if (!empty($btn['url'])) {
                $this->attributes['url'] = $btn['url'];
            }
            if (!empty($btn['target'])) {
                $this->attributes['target'] = $btn['target'];
            }
            if (!empty($btn['title'])) {
                $this->attributes['title'] = $btn['title'];
            }
        }

        $this->classList = $classes;
        $this->type = $type;
    }

    /**
     * Button types: primary (solid), secondary (outline), simple (text + arrow).
     */
    function output() {

        if (empty($this->attributes['url'])) {
            return;
        }

        $type = in_array($this->type, array('primary', 'secondary', 'simple'), true) ? $this->type : 'primary';
        $target = $this->attributes['target'] ?: '_self';
        $rel = ($target === '_blank') ? ' rel="noopener"' : '';
        ?>
        <a href="<?php echo esc_url($this->attributes['url']); ?>"
            target="<?php echo esc_attr($target); ?>"<?php echo $rel; ?>
            class="<?php echo esc_attr(trim('btn btn-' . $type . ' ' . $this->classList)); ?>">
            <span><?php echo esc_html($this->attributes['title'] ?? ''); ?></span>
            <svg width="3" height="6" viewBox="0 0 3 6" fill="none" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><path d="M0.394287 0.307495L2.3756 2.84767L0.394287 5.38784" /></svg>
        </a>
        <?php
    }
}


class Decoration {

}

class LeftUDecoration extends Decoration {

    function output() {
        ?>
        <img class='left-u-decoration' src="<?php echo esc_url( get_parent_theme_file_uri( 'assets/img/left-u-decoration.png' ) ); ?>" alt="" />
        <?php
    }

}

class RightCircleDecoration extends Decoration {

    function output() {
        ?>
        <img class='right-circle-decoration' src="<?php echo esc_url( get_parent_theme_file_uri( 'assets/img/right-circle-decoration.png' ) ); ?>" alt="" />
        <?php
    }

}