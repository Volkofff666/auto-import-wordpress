<?php
/**
 * Blocks Manager
 *
 * @package AutoImportCore
 */

namespace AIC\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class BlocksManager {
    
    public function __construct() {
        add_action('init', [$this, 'register_blocks']);
        add_filter('block_categories_all', [$this, 'add_block_category'], 10, 2);
    }
    
    /**
     * Register blocks
     */
    public function register_blocks() {
        $blocks = ['hero', 'trust-bar', 'car-grid', 'lead-form', 'articles-grid', 'faq'];
        
        foreach ($blocks as $block) {
            $block_path = AIC_PLUGIN_DIR . 'blocks/' . $block;
            
            if (file_exists($block_path . '/block.json')) {
                register_block_type($block_path, [
                    'render_callback' => [$this, 'render_' . str_replace('-', '_', $block)],
                ]);
            }
        }
    }
    
    /**
     * Add block category
     */
    public function add_block_category($categories, $context) {
        return array_merge(
            $categories,
            [
                [
                    'slug' => 'auto-import',
                    'title' => __('Auto Import Blocks', 'auto-import-core'),
                    'icon' => 'car',
                ],
            ]
        );
    }
    
    /**
     * Render Hero block
     */
    public function render_hero($attributes) {
        $title = $attributes['title'] ?? '';
        $subtitle = $attributes['subtitle'] ?? '';
        $buttonText = $attributes['buttonText'] ?? '';
        $buttonUrl = $attributes['buttonUrl'] ?? '';
        $backgroundImage = $attributes['backgroundImage'] ?? '';
        
        ob_start();
        ?>
        <section class="hero" style="<?php echo $backgroundImage ? 'background-image: url(' . esc_url($backgroundImage) . ');' : ''; ?>">
            <div class="hero__overlay"></div>
            <div class="container">
                <div class="hero__content">
                    <?php if ($title) : ?>
                        <h1 class="hero__title"><?php echo esc_html($title); ?></h1>
                    <?php endif; ?>
                    <?php if ($subtitle) : ?>
                        <p class="hero__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                    <?php if ($buttonText && $buttonUrl) : ?>
                        <a href="<?php echo esc_url($buttonUrl); ?>" class="btn btn--primary btn--large"><?php echo esc_html($buttonText); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render Trust Bar block
     */
    public function render_trust_bar($attributes) {
        $title = $attributes['title'] ?? '';
        $items = $attributes['items'] ?? [];
        
        ob_start();
        ?>
        <section class="trust-bar">
            <div class="container">
                <?php if ($title) : ?>
                    <h2 class="trust-bar__title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <?php if (!empty($items)) : ?>
                    <div class="trust-bar__items">
                        <?php foreach ($items as $item) : ?>
                            <div class="trust-bar__item">
                                <?php if (!empty($item['icon'])) : ?>
                                    <div class="trust-bar__icon"><?php echo \AIC\Helpers::get_icon($item['icon'], 48); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($item['title'])) : ?>
                                    <h3 class="trust-bar__item-title"><?php echo esc_html($item['title']); ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($item['text'])) : ?>
                                    <p class="trust-bar__item-text"><?php echo esc_html($item['text']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render Car Grid block
     */
    public function render_car_grid($attributes) {
        $numberOfCars = $attributes['numberOfCars'] ?? 6;
        $columns = $attributes['columns'] ?? 3;
        $orderBy = $attributes['orderBy'] ?? 'date';
        $showFilters = $attributes['showFilters'] ?? false;
        
        // Build query args
        $args = [
            'post_type' => 'car',
            'posts_per_page' => $numberOfCars,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => 'aic_show_in_catalog',
                    'value' => '1',
                    'compare' => '=',
                ],
            ],
        ];
        
        // Set order
        switch ($orderBy) {
            case 'price_asc':
                $args['meta_key'] = 'aic_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case 'price_desc':
                $args['meta_key'] = 'aic_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'year_desc':
                $args['meta_key'] = 'aic_year';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'year_asc':
                $args['meta_key'] = 'aic_year';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            default:
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
        }
        
        $query = new \WP_Query($args);
        
        ob_start();
        ?>
        <section class="car-grid-block">
            <div class="container">
                <?php if ($query->have_posts()) : ?>
                    <div class="car-grid car-grid--columns-<?php echo esc_attr($columns); ?>">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <?php get_template_part('template-parts/car', 'card'); ?>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p><?php _e('No cars found.', 'auto-import-core'); ?></p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render Lead Form block
     */
    public function render_lead_form($attributes) {
        $title = $attributes['title'] ?? '';
        $subtitle = $attributes['subtitle'] ?? '';
        $buttonText = $attributes['buttonText'] ?? __('Submit', 'auto-import-core');
        $showBudget = $attributes['showBudget'] ?? true;
        
        ob_start();
        ?>
        <section class="lead-form-block">
            <div class="container">
                <div class="lead-form-wrapper">
                    <?php if ($title) : ?>
                        <h2 class="lead-form__title"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ($subtitle) : ?>
                        <p class="lead-form__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                    
                    <form class="lead-form" id="aic-lead-form">
                        <div class="lead-form__field">
                            <input type="text" name="name" placeholder="<?php esc_attr_e('Your Name', 'auto-import-core'); ?>" required>
                        </div>
                        <div class="lead-form__field">
                            <input type="tel" name="phone" placeholder="<?php esc_attr_e('Phone Number', 'auto-import-core'); ?>" required>
                        </div>
                        <div class="lead-form__field">
                            <input type="email" name="email" placeholder="<?php esc_attr_e('Email (optional)', 'auto-import-core'); ?>">
                        </div>
                        <?php if ($showBudget) : ?>
                            <div class="lead-form__field">
                                <input type="number" name="budget" placeholder="<?php esc_attr_e('Budget (RUB)', 'auto-import-core'); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="lead-form__field">
                            <textarea name="comment" rows="4" placeholder="<?php esc_attr_e('Your Message', 'auto-import-core'); ?>"></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary btn--block"><?php echo esc_html($buttonText); ?></button>
                        <div class="lead-form__message" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render Articles Grid block
     */
    public function render_articles_grid($attributes) {
        $numberOfPosts = $attributes['numberOfPosts'] ?? 6;
        $columns = $attributes['columns'] ?? 3;
        
        $query = new \WP_Query([
            'post_type' => 'article',
            'posts_per_page' => $numberOfPosts,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        ob_start();
        ?>
        <section class="articles-grid-block">
            <div class="container">
                <?php if ($query->have_posts()) : ?>
                    <div class="articles-grid articles-grid--columns-<?php echo esc_attr($columns); ?>">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <article class="article-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="article-card__image">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="article-card__content">
                                    <h3 class="article-card__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="article-card__meta">
                                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                    </div>
                                    <?php if (has_excerpt()) : ?>
                                        <p class="article-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php the_permalink(); ?>" class="btn btn--secondary"><?php _e('Read More', 'auto-import-core'); ?></a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p><?php _e('No articles found.', 'auto-import-core'); ?></p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render FAQ block
     */
    public function render_faq($attributes) {
        $items = $attributes['items'] ?? [];
        
        ob_start();
        ?>
        <section class="faq-block">
            <div class="container">
                <?php if (!empty($items)) : ?>
                    <div class="faq-list">
                        <?php foreach ($items as $index => $item) : ?>
                            <div class="faq-item">
                                <button class="faq-item__question" type="button" aria-expanded="false" aria-controls="faq-<?php echo esc_attr($index); ?>">
                                    <?php echo esc_html($item['question'] ?? ''); ?>
                                    <span class="faq-item__icon">+</span>
                                </button>
                                <div class="faq-item__answer" id="faq-<?php echo esc_attr($index); ?>" style="display: none;">
                                    <p><?php echo esc_html($item['answer'] ?? ''); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
}
