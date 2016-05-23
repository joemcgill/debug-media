<?php

class Debug_Bar_Media extends Debug_Bar_Panel {

	private $editor;

	private $editor_version;

	function init() {
		$this->title( 'Media' );
	}

	function render() {
	?>
	<h1>Media Debugging Info</h1>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Editor</th>
				<td><?php echo $this->get_current_editor(); ?></td>
			</tr>
			<tr>
				<th scope="row">Editor Version</th>
				<td><?php echo $this->get_editor_version(); ?></td>
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

	function get_editor_version() {
		// Make sure the current editor has been set.
		if ( ! $this->editor ) {
			$this->get_current_editor();
		}

		// Return early if we already know the editor version.
		if ( $this->editor_version ) {
			return $this->editor_version;
		}

		if ( 'WP_Image_Editor_Imagick' === $this->editor ) {
			$imagick = new Imagick();
			$this->editor_version = implode( ', ', $imagick->getVersion() );
		} elseif ( 'WP_Image_Editor_GD' === $this->editor ) {
			$gd = gd_info();
			$this->editor_version = $gd['GD Version'];
		} else {
			$this->editor_version = 'Not Available';
		}

		return $this->editor_version;
	}

	function get_memory_limit() {
		return ini_get( 'memory_limit' );
	}
}
