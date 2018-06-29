<?php


/**
 * Customizer separator
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}
class LSX_Landing_Pages_Box_Control extends WP_Customize_Control {
	/**
	 * @access public
	 * @var string
	 */
	public $type = 'sepearator';

	/**
	 * @access public
	 * @var array
	 */
	public $statuses;

	/**
	 * @access public
	 * @var array
	 */
	public $text = array();

	/**
	 * Constructor.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string $id
	 * @param array $args
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		if ( ! empty( $args['choices'] ) ) {
			$this->layouts = $args['choices'];
		}
	}
	/**
	 * Render output
	 *
	 * @since 3.4.0
	 */
	public function render_content() {

		$class = 'accordion-section accordion-section-' . $this->type;

		?>
		<h2 class="<?php echo esc_attr( $class ); ?>">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
		</h2>
	<?php
	}

}
