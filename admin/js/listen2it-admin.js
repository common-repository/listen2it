(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function(){

		$('#l2it-btn-refresh-settings').click(function (e){

			window.location.reload();

		});

		$('.audio-actions').each(function (index, element){

			var self = $(this);

			var content_id = $(this).data('content-id');
			var post_id = $(this).data('post-id');

			var data = {
				'action': 'get_audio_status',
				'content_id': content_id,
				'post_id': post_id
			};

			$.post(ajax_object.ajax_url, data, function (response){

				response = JSON.parse(response);

				if(response.hasOwnProperty('success') && true === response.success){

					if(false == response.data.is_audio_disabled){

						var innerHTML = '<span class="enabled">Enabled</span>\n';

						var contentUpdateAt = moment(response.data.updated_at);
						var postUpdatedAt = moment.utc(response.data.post_last_modified_at);

						if(postUpdatedAt.isAfter(contentUpdateAt)){

							innerHTML += '<br/><br/><span class="stale">Stale Audio</span><span class="dashicons dashicons-info audio-update" id="stale"></span>\n';
						
						}

						innerHTML += '<p class="audio-setting"><a href="' + ajax_object.content_url + content_id + '" target="_blank">Audio Settings</a></p>'

						self.append(innerHTML);

						$(".audio-update").hover(function() {
							$(this).css('cursor','pointer').attr('title', 'Regenerate audio to \nsync with updated post');
						}, function() {
							$(this).css('cursor','auto');
						});

					} else {
						var innerHTML = '<span class="disabled">Disabled</span>\n<p class="audio-setting"><a href="' + ajax_object.content_url + content_id + '" target="_blank">Audio Settings</a></p>';
						self.append(innerHTML);
					}

				} else {

					self.append('<span class="no-audio">Audio Pending</span><span class="dashicons dashicons-info audio-pending" id="myId"></span>')

					$(".audio-pending").hover(function() {
						$(this).css('cursor','pointer').attr('title', 'Audio will be generated automatically, \nWhen user hits play for the first time.');
					}, function() {
						$(this).css('cursor','auto');
					});
				}

			});

		});

	});



})( jQuery );
