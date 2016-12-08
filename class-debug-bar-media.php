<?php
/**
 * Main class used to implement the Media Debug Bar panel.
 *
 * @since 0.1.0
 */
class Debug_Bar_Media extends Debug_Bar_Panel {

	/**
	 * The current active `WP_Image_Edtor`
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string
	 */
	private $editor = '';

	/**
	 * Imagick version info.
	 *
	 * @since  0.2.0
	 * @access private
	 * @var    Imagick|string
	 */
	private $imagick = '';

	/**
	 * Imagick version info.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array|string
	 */
	private $imagick_version = '';

	/**
	 * GD version info.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string
	 */
	private $gd_version = '';

	/**
	 * Ghostscript version info.
	 *
	 * @since  0.3.0
	 * @access private
	 * @var    string
	 */
	private $gs_version = '';

	/**
	 * Initialize the data for the Media debug panel.
	 *
	 * @since  0.1.0
	 * @access public
	 */
	public function init() {
		$this->title( 'Media' );

		// Preload the data.
		$this->get_current_editor();
		$this->get_imagick_version();
		$this->get_gd_version();
		$this->get_gs_version();
	}

	/**
	 * Output the content of the Media debug panel.
	 *
	 * @since  0.1.0
	 * @access public
	 */
	public function render() {
	?>
	<h1>Media Debugging Info</h1>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Active Editor</th>
				<td><?php echo $this->editor; ?></td>
			</tr>
			<tr>
				<th scope="row">Imagick Module Number</th>
				<td><?php echo ( is_array( $this->imagick_version ) ) ? $this->imagick_version['versionNumber'] : $this->imagick_version; ?></td>
			</tr>
			<tr>
				<th scope="row">ImageMagick Version</th>
				<td><?php echo ( is_array( $this->imagick_version ) ) ? $this->imagick_version['versionString'] : $this->imagick_version; ?></td>
			</tr>
			<tr>
				<th scope="row">GD Version</th>
				<td><?php echo $this->gd_version; ?></td>
			</tr>
			<tr>
				<th scope="row">Ghostscript Version</th>
				<td><?php echo $this->gs_version; ?></td>
			</tr>
			<tr>
				<th scope="row">Memory Limit</th>
				<td><?php echo ini_get( 'memory_limit' ); ?></td>
			</tr>
			<tr>
				<th scope="row">Max Execution Time</th>
				<td><?php echo ini_get( 'max_execution_time' ); ?></td>
			</tr>
			<tr>
				<th scope="row">Max Input Time</th>
				<td><?php echo ini_get( 'max_input_time' ); ?></td>
			</tr>
			<tr>
				<th scope="row">Upload Max Filesize</th>
				<td><?php echo ini_get( 'upload_max_filesize' ); ?></td>
			</tr>
			<tr>
				<th scope="row">Post Max Size</th>
				<td><?php echo ini_get( 'post_max_size' ); ?></td>
			</tr>
		</tbody>
	</table>

	<?php if ( 'WP_Image_Editor_Imagick' === $this->editor ) : ?>
	<h2>Imagick Resource Limits</h2>
	<table class="form-table">
		<tbody>
		<?php
		$limits = $this->get_imagick_resource_limits();

		foreach ( $limits as $limit => $value ) { ?>
			<tr>
				<th scope="row">Imagick <?php echo esc_html( $limit ) ?>  limit</th>
				<td><?php echo esc_html( $value ); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php endif; ?>

	<?php
	}

	/**
	 * Return the name of the active `WP_Image_Edtor`.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string The active image editor class name (`WP_Image_Editor_Imagick` or `WP_Image_Edtor_GD`).
	 */
	private function get_current_editor() {
		if ( ! $this->editor ) {
			$this->editor = _wp_image_editor_choose();
		}
		return $this->editor;
	}

	/**
	 * Return version information for Imagick if installed.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array|string An array containing `Imagick::getVersion()` info or 'Imagick not available'.
	 */
	private function get_imagick_version() {
		// Make sure the current editor has been set.
		if ( ! $this->editor ) {
			$this->get_current_editor();
		}

		// Return early if we already know the Imagick version.
		if ( ! $this->imagick_version ) {
			if ( class_exists( 'Imagick' ) ) {
				// Save the Imagick instance for later use.
				$this->imagick = new Imagick();
				$this->imagick_version = $this->imagick->getVersion();
			} else {
				$this->imagick_version = 'Imagick not available';
			}
		}

		return $this->imagick_version;
	}

	/**
	 * Return the current version of GD installed.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string The current GD version or 'GD not available'.
	 */
	private function get_gd_version() {
		// Make sure the current editor has been set.
		if ( ! $this->editor ) {
			$this->get_current_editor();
		}

		// Return early if we already know the GD version.
		if ( ! $this->gd_version ) {
			$gd = gd_info();
			$this->gd_version = is_array( $gd ) ? $gd['GD Version'] : 'GD not available';
		}

		return $this->gd_version;
	}

	/**
	 * Return the current version of Ghostscript installed.
	 *
	 * @since  0.3.0
	 * @access private
	 * @return string The current Ghostscript version if available.
	 */
	private function get_gs_version() {
		if ( ! $this->gs_version ) {
			$gs = exec( 'gs --version' );

			$this->gs_version = ( ! empty( $gs ) ) ? $gs : 'Not available';
		}

		return $this->gs_version;
	}

	/**
	 * Return the Imagick resource limits
	 *
	 * @since  0.2.0
	 * @access private
	 * @return array
	 */
	private function get_imagick_resource_limits() {
		static $limits = array();

		if ( empty( $limits ) && $this->imagick instanceof Imagick ) {
				$limits['area']   = ( defined( 'imagick::RESOURCETYPE_AREA' ) )   ? size_format( $this->imagick->getResourceLimit( imagick::RESOURCETYPE_AREA ) ) : 'Not Available';
				$limits['disk']   = ( defined( 'imagick::RESOURCETYPE_DISK' ) )   ? $this->imagick->getResourceLimit( imagick::RESOURCETYPE_DISK ) : 'Not Available';
				$limits['file']   = ( defined( 'imagick::RESOURCETYPE_FILE' ) )   ? $this->imagick->getResourceLimit( imagick::RESOURCETYPE_FILE ) : 'Not Available';
				$limits['map']    = ( defined( 'imagick::RESOURCETYPE_MAP' ) )    ? size_format( $this->imagick->getResourceLimit( imagick::RESOURCETYPE_MAP ) ) : 'Not Available';
				$limits['memory'] = ( defined( 'imagick::RESOURCETYPE_MEMORY' ) ) ? size_format( $this->imagick->getResourceLimit( imagick::RESOURCETYPE_MEMORY ) ) : 'Not Available';
				$limits['thread'] = ( defined( 'imagick::RESOURCETYPE_THREAD' ) ) ? $this->imagick->getResourceLimit( imagick::RESOURCETYPE_THREAD ) : 'Not Available';
		}

		return $limits;
	}
}
