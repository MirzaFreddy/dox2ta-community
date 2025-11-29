# Dox2ta Community

A lightweight WordPress plugin that provides a shortcode to let logged‑in users join the "Dox2ta" community with a gamified Dota 2‑themed UI. It stores the join order in a custom database table and displays the member’s sequential join number after a successful join.

## Features

- Shortcode: `dox2ta_community` renders a one‑click join UI
- Ajax join flow with nonce verification (no page reload)
- Stores users in a custom table with a stable sequential `join_number`
- Displays the user’s join number on success
- Enqueued assets only when the shortcode is used
- Localization‑ready (text domain `dox2ta-community`)

## Requirements

- WordPress 5.5+
- PHP 7.4+

## Installation

1. Copy the plugin folder `dox2ta-community` into `wp-content/plugins/`.
2. In WordPress Admin → Plugins, activate “Dox2ta Community”.
   - On activation, the plugin creates a table: `{wp_prefix}dox2ta_members`.

## Usage

Insert the shortcode in a post/page or a block:

```
[dox2ta_community]
```

Behavior:

- If the visitor is not logged in, a Login button appears (links to the current page’s login URL).
- If logged in, a “Join Community” button triggers an Ajax request.
- On success, the component displays the user’s `join_number`.

## How it works (Overview)

- Main plugin file: `dox2ta-community.php`
  - Registers assets and loads the shortcode class on `init`
  - Sets up activation hook to create the DB table
- Activation: `includes/class-dox2ta-activator.php`
  - Creates `{prefix}dox2ta_members (id, user_id, join_number, joined_at)`
- Shortcode + Ajax: `includes/class-dox2ta-shortcode.php`
  - Shortcode: `dox2ta_community`
  - Ajax actions: `dox2ta_join` for both logged and non‑logged users
  - Localizes script with `admin-ajax.php`, nonce, and texts
- UI Template: `templates/shortcode.php`
  - Renders the component container and buttons
- Frontend JS: `assets/js/dox2ta-community.js`
  - Handles the click, calls `admin-ajax.php?action=dox2ta_join` and shows the result
- Styles: `assets/css/dox2ta-community.css`

## Database

Table: `{prefix}dox2ta_members`

- `id` BIGINT UNSIGNED AUTO_INCREMENT (also used as the stable `join_number`)
- `user_id` BIGINT UNSIGNED UNIQUE
- `join_number` BIGINT UNSIGNED (mirrors `id` for clarity)
- `joined_at` DATETIME

## Localization

- Text domain: `dox2ta-community`
- Load path: `languages/`
- Use standard WordPress tools (e.g., Poedit, WP-CLI) to generate `.po/.mo` files.

## Development

- Enqueued handles: `dox2ta-community` (CSS/JS)
- Scripts are only registered at `init` and enqueued when the shortcode renders
- Ajax action: `dox2ta_join`
- Nonce: `dox2ta_join_nonce`

## Security Notes

- Ajax requests are protected with a nonce and require login for joining
- Always keep WordPress core and plugins up to date

## License

GPLv2

## Credits

Author: MirzaFreddy
