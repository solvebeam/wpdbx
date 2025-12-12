<?php
/**
 * Row data
 *
 * @package SolveBeam\WpdbX
 * @author SolveBeam
 * @copyright 2025 SolveBeam
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0 or later
 */

declare(strict_types=1);

namespace SolveBeam\WpdbX;

/**
 * Row data class
 */
final class RowData {
	/**
	 * Data.
	 *
	 * @var array
	 */
	private array $data = [];

	/**
	 * WordPress row format.
	 *
	 * @var array<string, string>
	 */
	private array $format = [];

	/**
	 * Set.
	 *
	 * @param string $name   Name.
	 * @param mixed  $value  Value.
	 * @param string $format Format.
	 * @return self
	 */
	public function set( string $name, $value, $format = '%s' ) {
		$this->data[ $name ]   = $value;
		$this->format[ $name ] = $value;

		return $this;
	}

	/**
	 * Get data.
	 *
	 * @return array
	 */
	public function get_data(): array {
		return $this->data;
	}

	/**
	 * Get format.
	 *
	 * @return array<string, string>
	 */
	public function get_format(): array {
		return $this->format;
	}
}
