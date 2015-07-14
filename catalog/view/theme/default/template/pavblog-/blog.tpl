<?php echo $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div class="row">
		<?php echo $column_left; ?>
		
		<?php if ($column_left && $column_right) { ?>
		<?php $cols = 6; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $cols = 9; ?>
		<?php } else { ?>
		<?php $cols = 12; ?>
		<?php } ?>
		<div class="col-xs-<?php echo $cols; ?> ">
			<div id="content">
				<?php echo $content_top; ?>
				<div class="row">
					<div class="heading container">
						<h1><?php echo $heading_title; ?></h1>
					</div>
				</div>
				<div class="row">
					<div class="pav-blog col-xs-12">
						<?php if( $blog['thumb_large'] ) { ?>
						<div class="image">
							<img src="<?php echo $blog['thumb_large'];?>" title="<?php echo $blog['title'];?>"/>
						</div>
						<?php } ?>
						
						<div class="blog-meta btn-group">
							
							<?php if( $config->get('blog_show_author') ) { ?>
							<span class="author btn btn-default"><span><?php echo $this->language->get("text_write_by");?></span> <?php echo $blog['author'];?></span>
							<?php } ?>
							<?php if( $config->get('blog_show_category') ) { ?>
							<span class="publishin btn btn-default">
								<span><?php echo $this->language->get("text_published_in");?></span>
								<a href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a>
							</span>
							<?php } ?>
							<?php if( $config->get('blog_show_created') ) { ?>
							<span class="created btn btn-default"><span><?php echo $this->language->get("text_created_date");?> <?php echo $blog['created'];?></span></span>
							<?php } ?>
							<?php if( $config->get('blog_show_hits') ) { ?>
							<span class="hits btn btn-default"><span><?php echo $this->language->get("text_hits");?></span> <?php echo $blog['hits'];?></span>
							<?php } ?>
							<?php if( $config->get('blog_show_comment_counter') ) { ?>
							<span class="comment_count btn btn-default"><span><?php echo $this->language->get("text_comment_count");?></span> <?php echo $blog['comment_count'];?></span>
							<?php } ?>
							<?php if( !empty($tags) ) { ?>
							<span class="blog-tags btn btn-default">
								<b><?php echo $this->language->get('text_tags');?></b>
								<?php foreach( $tags as $tag => $tagLink ) { ?>
								<a href="<?php echo $tagLink; ?>" title="<?php echo $tag; ?>"><?php echo $tag; ?></a>
								<?php } ?>
							</span>
							<?php } ?>
							<span class="social-wrap btn btn-default">
								<b><?php echo $this->language->get('text_like_this');?> </b>
								<div class="share42init"  data-title="<?php print $heading_title; ?>" data-description="<?php print strip_tags(html_entity_decode($description));?>" data-path="/catalog/view/theme/default/image/share" data-image="<?php echo $blog['thumb_large'];?>"></div>
								<script type="text/javascript" src="/catalog/view/javascript/share42.js"></script>
							</span>
						</div>
						
						<div class="description clearfix"><?php echo $description;?></div>
						<div class="blog-content clearfix">
							<div class="content-wrap clearfix">
								<?php echo $content;?>
							</div>
							<?php if( $blog['video_code'] ) { ?>
							<div class="pav-video clearfix"><?php echo html_entity_decode($blog['video_code'], ENT_QUOTES, 'UTF-8');?></div>
							<?php } ?>
						</div>
						<div class="row">
							<div class="container">
								<div class="blog-social col-xs-6">
								</div>
							</div>
						</div>
						<div class="blog-bottom clearfix">
							<?php if( !empty($samecategory) ) { ?>
							<div class="pavcol2">
								<div class="heading"><span><?php echo $this->language->get('text_in_same_category');?></span></div>
								<ul>
									<?php foreach( $samecategory as $item ) { ?>
									<li><a href="<?php echo $this->url->link('pavblog/blog',"id=".$item['blog_id']);?>"><?php echo $item['title'];?></a></li>
									<?php } ?>
								</ul>
							</div>
							<?php } ?>
							<?php if( !empty($related) ) { ?>
							<div class="pavcol2">
								<div class="heading"><span><?php echo $this->language->get('text_in_related_by_tag');?></span></div>
								<ul>
									<?php foreach( $related as $item ) { ?>
									<li><a href="<?php echo $this->url->link('pavblog/blog',"id=".$item['blog_id']);?>"><?php echo $item['title'];?></a></li>
									<?php } ?>
								</ul>
							</div>
							<?php } ?>
						</div>
						<div class="pav-comment">
							<?php if( $config->get('blog_show_comment_form') ) { ?>
							<?php if( $config->get('comment_engine') == 'diquis' ) { ?>
							<div id="disqus_thread"></div>
							<script type="text/javascript">
								/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
						var disqus_shortname = '<?php echo $config->get('diquis_account');?>'; // required: replace example with your forum shortname

						/* * * DON'T EDIT BELOW THIS LINE * * */
						(function() {
							var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
							dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
							(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
					
					<?php } elseif( $config->get('comment_engine') == 'facebook' ) { ?>
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) {return;}
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $config->get("facebook_appid");?>";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-comments" data-href="<?php echo $link; ?>" 
						data-num-posts="<?php echo $config->get("comment_limit");?>" data-width="<?php echo $config->get("facebook_width")?>">
					</div>
					<?php }else { ?>
					<?php if( count($comments) ) { ?>
					<div class="heading"><span><?php echo $this->language->get('text_list_comments'); ?></span></div>
					<div class="pave-listcomments">
						<?php foreach( $comments as $comment ) {  $default='';?>
						<div class="comment-item clearfix" id="comment<?php echo $comment['comment_id'];?>">
							
							<img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $comment['email'] ) ) ) . "?d=" . urlencode( $default ) . "&s=60" ?>" align="left"/>
							<div class="comment-wrap">
								<div class="comment-meta">
									<span class="comment-created"><?php echo $this->language->get('text_created');?> <span><?php echo $comment['created'];?></span></span>
									<span class="comment-postedby"><?php echo $this->language->get('text_postedby');?> <span><?php echo $comment['user'];?></span></span>
									<span class="comment-link"><a href="<?php echo $link;?>#comment<?php echo $comment['comment_id'];?>"><?php echo $this->language->get('text_comment_link');?></a></span>
								</div>
								<?php echo $comment['comment'];?>
							</div>
						</div>
						<?php } ?>
						<div class="pagination">
							<?php echo $pagination;?>
						</div>
					</div>
					<?php } ?>
					<div class="heading"><span><?php echo $this->language->get("text_leave_a_comment");?></span></div>
					<form action="<?php echo $comment_action;?>" method="post" id="comment-form">
						<fieldset class="row">
							<div class="container"> 
								<div class="message" style="display:none"></div>
								<div class="form-group required row">
									<label class="col-xs-2 control-label" for="comment-user"><?php echo $this->language->get('entry_name');?></label>
									<div class="col-xs-6"><input class="form-control" type="text" name="comment[user]" value="" id="comment-user"/></div>
								</div>
								<div class="form-group required row">
									<label class="col-xs-2 control-label" for="comment-email"><?php echo $this->language->get('entry_email');?></label>
									<div class="col-xs-6"><input class="form-control" type="text" name="comment[email]" value="" id="comment-email"/></div>
								</div>
								<div class="form-group required row">
									<label class="col-xs-2 control-label" for="comment-comment"><?php echo $this->language->get('entry_comment');?></label>
									<div class="col-xs-6"><textarea class="form-control" name="comment[comment]"  id="comment-comment"></textarea></div>
								</div>
								<?php if( $config->get('enable_recaptcha') ) { ?>
								<div class="form-group required  row"> 
									<label class="col-xs-2 control-label"><?php echo $entry_captcha; ?></label>
									<div class="col-xs-2"><input type="text" class="form-control"  name="captcha" value="<?php echo $captcha; ?>" size="10" /></div>
									<div class="col-xs-2 captcha"><img src="index.php?route=pavblog/blog/captcha" alt="" id='captcha' /></div>
								</div>
								<script>
									$(document).ready(function(){
										$('#captcha').before('<img src="/catalog/view/theme/default/image/refresh.png" id="ref" />');
										$('#ref').click(function() {$('#captcha').attr('src', 'index.php?route=product/product/captcha&rand='+ Math.round((Math.random() * 10000 )));});
									});
								</script>
								<?php } ?>
								<input type="hidden" name="comment[blog_id]" value="<?php echo $blog['blog_id']; ?>" />
							</div>
							<div class="container buttons">
								<div class="col-xs-4"></div>
								<div class="col-xs-4"> 
									<button class="btn btn-primary" type="submit">
										<span><?php echo $this->language->get('text_submit');?></span>
									</button>
								</div>
							</div>
						</fieldset>
					</form>
					<script type="text/javascript">
						$( "#comment-form .message" ).hide();
						$("#comment-form").submit( function(){
							$.ajax( {type: "POST",url:$("#comment-form").attr("action"),data:$("#comment-form").serialize(), dataType: "json",}).done( function( data ){
								if( data.hasError ){
									$( "#comment-form .message" ).html( data.message ).show();	
								}else {
									location.href='<?php echo str_replace("&amp;","&",$link);?>';
								}
							} );
							return false;
						} );
						
					</script>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>

		
		<?php echo $content_bottom; ?>
	</div>
</div>
<?php echo $column_right; ?>
</div>
</div>
<?php echo $footer; ?> 