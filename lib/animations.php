<?php
add_action( 'cmb2_admin_init', function(){
	$cmbSiteAnim = new_cmb2_box( array(
		'id'           => 'adpSiteAnimationsMetaBox',
		'title'        => esc_html__( 'Site Animations', '' ),
		'object_types' => array( 'options-page' ),
		'option_key'  => 'adpSiteAnimations'
	) );
	$cmbSiteAnim->add_field( array(
		'name' => 'Play Once',
		'id'   => 'animPlayOnce',
		'type' => 'select',
		'options' => array(
			'no'=>'No',
			'yes'=>'Yes'
		)
	) );
	$group_field_id_SA = $cmbSiteAnim->add_field( array(
		'id'          => 'adpAnimationsList',
		'type'        => 'group',
		'description' => __( 'Animations', 'cmb2' ),
		'options'     => array(
			'group_title'       => __( 'Animation {#}', 'cmb2' ),
			'add_button'        => __( 'Add Another Animation', 'cmb2' ),
			'remove_button'     => __( 'Remove Animation', 'cmb2' ),
			'sortable'          => true
		),
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Name',
		'id'   => 'animsecname',
		'type' => 'text'
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Animation',
		'id'   => 'animation',
		'type' => 'select',
		'options' => adpGetAnimationsList()
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Easing',
		'id'   => 'easing',
		'type' => 'select',
		'options' => getEasingsList()
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Anchor Placement',
		'id'   => 'anchorplacement',
		'type' => 'select',
		'options' => adpGetAncherPlacements()
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Offset',
		'id'   => 'offset',
		'type' => 'text',
		'default' => '200',
		'attributes' => array(
			'type' => 'number'
		)
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Delay',
		'id'   => 'delay',
		'type' => 'text',
		'default' => '50',
		'attributes' => array(
			'type' => 'number',
			'min'  => '1',
		)
	) );
	$cmbSiteAnim->add_group_field( $group_field_id_SA, array(
		'name' => 'Duration',
		'id'   => 'duration',
		'type' => 'text',
		'default' => '1000',
		'attributes' => array(
			'type' => 'number',
			'min'  => '1',
		)
	) );
});
function adpGetSiteAnimations() {
	$default = false;
	$key = 'adpAnimationsList';
	if ( function_exists( 'cmb2_get_option' ) ) {
		return cmb2_get_option( 'adpSiteAnimations', $key, $default );
	}
	$opts = get_option( 'adpSiteAnimations', $default );
	$val = $default;
	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}
	return $val;
}
function adpParseAnimAttributes($settings,$animPrefix,$delayMulti=''){
	$animation = $settings[$animPrefix.'animation'];
	$easing = $settings[$animPrefix.'easing'];
	$anchor_placement = $settings[$animPrefix.'anchor_placement'];
	$offset = $settings[$animPrefix.'offset'];
	$delay = $settings[$animPrefix.'delay'];
	if($delayMulti!=''){
		$delayMultiNum = (float) $delayMulti;
		$delayNum = (int) $delay;
		$delay = $delayNum * $delayMultiNum;
	}
	$duration = $settings[$animPrefix.'duration'];
	return 'data-aos-once="true" data-aos="'.$animation.'" data-aos-offset="'.$offset.'" data-aos-delay="'.$delay.'" data-aos-duration="'.$duration.'" data-aos-easing="'.$easing.'" data-aos-anchor-placement="'.$anchor_placement.'"';
}
function adpGetSiteSectionAnimation($parse=true){
	$retVal = [];
	$siteAnimations = adpGetSiteAnimations();
	if(is_array($siteAnimations) && count($siteAnimations) > 0){
		foreach($siteAnimations as $anim){
			if(!$parse){
				$retVal[$anim['animsecname']] = $anim;
			}else{
				$retVal[$anim['animsecname']] = 'data-aos-once="true" data-aos="'.$anim['animation'].'" data-aos-offset="'.$anim['offset'].'" data-aos-delay="'.$anim['delay'].'" data-aos-duration="'.$anim['duration'].'" data-aos-easing="'.$anim['easing'].'" data-aos-anchor-placement="'.$anim['anchorplacement'].'"';
			}
		}
	}
	return $retVal;
}
add_action('wp_footer', function(){
	?>
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script>
		jQuery(document).ready(function(){
			AOS.init();
		});
	</script>
	<?php
});