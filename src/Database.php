<?php
/**
 * Database
 *
 * @package SolveBeam\WpdbX
 * @author SolveBeam
 * @copyright 2025 SolveBeam
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0 or later
 */

declare(strict_types=1);

namespace SolveBeam\WpdbX;

use wpdb;

/**
 * Database class
 */
final class Database {
	/**
	 * WordPress database object.
	 *
	 * @var wpdb
	 */
	private wpdb $wpdb;

	/**
	 * Construct database wrapper.
	 *
	 * @param wpdb $wpdb WordPress database object.
	 */
	public function __construct( wpdb $wpdb ) {
		$this->wpdb = $wpdb;
	}

	/**
	 * Create database wrapper from global $wpdb.
	 *
	 * @return self
	 */
	public static function from_global_wpdb(): self {
		global $wpdb;

		return new self( $wpdb );
	}

	/**
	 * Insert.
	 */
	public function insert( string $table, array $data, array $format = [] ): int {
		$result = $this->wpdb->insert( $table, $data, $format );

		if ( $result === false ) {
			throw new QueryException(
				$this->wpdb->last_error ?: 'Unknown database error',
				$this->wpdb->last_query
			);
		}

		return (int) $this->wpdb->insert_id;
	}
}
