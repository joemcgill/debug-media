<?php

class Debug_Bar_Media extends Debug_Bar_Panel {

	private $editor;

	private $imagick_version;

	private $gd_version;

	function init() {
		$this->title( 'Media' );

		// Preload the data.
		$this->get_current_editor();
		$this->get_imagick_version();
		$this->get_gd_version();
	}

	function render() {
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
	<?php
	}

	function get_current_editor() {
		if ( ! $this->editor ) {
			$this->editor = _wp_image_editor_choose();
		}
		return $this->editor;
	}

	function get_imagick_version() {
		// Make sure the current editor has been set.
		if ( ! $this->editor ) {
			$this->get_current_editor();
		}

		// Return early if we already know the Imagick version.
		if ( ! $this->imagick_version ) {
			if ( class_exists( 'Imagick' ) ) {
				$imagick = new Imagick();
				$this->imagick_version = $imagick->getVersion();
			} else {
				$this->imagick_version = 'Imagick not available';
			}
		}

		return $this->imagick_version;
	}

	function get_gd_version() {
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
}
