<?php
$login_url = wp_login_url( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
?>
<div class="dox2ta-community" data-component="dox2ta">
  <div class="dox2ta-stage">
    <div class="dox2ta-content">
      <h2 class="dox2ta-title"><?php echo esc_html__( 'ما اینجا یک سرزمین دوتایی ساختیم', 'dox2ta-community' ); ?></h2>
      <p class="dox2ta-subtitle"><?php echo esc_html__( 'اگر تا اینجا اسکرول کردی  یعنی تو آماده‌ای و ما هم تو رو کم داریم!', 'dox2ta-community' ); ?></p>

      <ul class="dox2ta-checklist" aria-label="<?php echo esc_attr__( 'ویژگی‌های سرزمین دوتایی', 'dox2ta-community' ); ?>">
        <li><?php echo esc_html__( 'جامعه فعال از بازیکنان و هواداران Dota 2', 'dox2ta-community' ); ?></li>
        <li><?php echo esc_html__( 'چالش‌ها و رویدادهای سرگرم‌کننده', 'dox2ta-community' ); ?></li>
        <li><?php echo esc_html__( 'جوایز و لیدربورد عضویت', 'dox2ta-community' ); ?></li>
      </ul>

      <div class="dox2ta-result" role="status" aria-live="polite"></div>

      <?php if ( ! is_user_logged_in() ) : ?>
        <a class="dox2ta-btn dox2ta-btn-login dox2ta-btn-block" href="<?php echo esc_url( $login_url ); ?>"><?php echo esc_html__( 'ورود', 'dox2ta-community' ); ?></a>
      <?php else: ?>
        <button class="dox2ta-btn dox2ta-btn-join dox2ta-btn-block" type="button" data-action="join">
          <span class="flare"></span>
          <?php echo esc_html__( 'به کامیونیتی اضافه شو', 'dox2ta-community' ); ?>
        </button>
      <?php endif; ?>
    </div>
  </div>
</div>
