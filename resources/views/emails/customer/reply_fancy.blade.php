<html lang="en">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="initial-scale=1.0">
    <style>
        p { margin:0 0 1.6em 0; }
        pre { font-family: Menlo, Monaco, monospace, sans-serif; padding: 0 0 1.6em 0; color:#333333; line-height:15px; }
        img { max-width:100%; }
        a { color: #346CC4; text-decoration:none; }
    </style>
</head>
<body style="-webkit-text-size-adjust:none;">
	<table cellspacing="0" border="0" cellpadding="0" width="100%">
	    <tr>
	        <td>
	            <table id="{{ App\Misc\Mail::REPLY_SEPARATOR_HTML }}" class="{{ App\Misc\Mail::REPLY_SEPARATOR_HTML }}" width="100%" border="0" cellspacing="0" cellpadding="0">
	            	@foreach ($threads as $thread)
		            	<tr>
						    <td>
						        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0;">
						        	@if (!$loop->first)
							            <tr>
							                <td style="padding:8px 0 10px 0;">
							                	
							                    <h3 style="font-family:Arial, 'Helvetica Neue', Helvetica, Tahoma, sans-serif; color:#727272; font-size:15px; line-height:21px; margin:0; font-weight:normal;">
							                    	<strong style="color:#000000;">{{ $thread->getFromName($mailbox) }}</strong>
							                    	{{--if ($loop->last){!! __(':person sent a message', ['person' => '<strong style="color:#000000;">'.htmlspecialchars($thread->getFromName($mailbox)).'</strong>']) !!}@else {!! __(':person replied', ['person' => '<strong style="color:#000000;">'.htmlspecialchars($thread->getFromName($mailbox)).'</strong>']) !!}@endif--}}
							                	</h3>

							                    @if ($thread->getCcArray())
								                    <p style="font-family:Arial, 'Helvetica Neue', Helvetica, Tahoma, sans-serif; color:#9F9F9F; font-size:12px; line-height:16px; margin:0;">
								                    	Cc: {{ implode(', ', $thread->getCcArray() )}}
								                    	<br>
								                    </p>
								                @endif
							                </td>
							                <td style="padding:8px 0 10px 0;" valign="top">
							                    <div style="font-family: Arial, 'Helvetica Neue', Helvetica, Tahoma, sans-serif; color:#9F9F9F; font-size:12px; line-height:18px; margin:0;" align="right">{{ App\Customer::dateFormat($thread->created_at, 'M j, H:i') }}</div>
							                </td>
							            </tr>
							        @endif
						            <tr>
						                <td colspan="2" style="padding:8px 0 10px 0;">
						                    <div>
						                        <div style="font-family:Arial, 'Helvetica Neue', Helvetica, Tahoma, sans-serif; color: #232323; font-size:13px; line-height:19px; margin:0;">
						                        	@if ($thread->source_via == App\Thread::PERSON_USER && $mailbox->before_reply)
						                                <span style="color:#b5b5b5">{{ $mailbox->before_reply }}</span><br><br>
						                            @endif
					                                {!! $thread->body !!}

					                                @action('reply_email.before_signature', $thread, $loop, $threads, $conversation, $mailbox)
					                                @if ($thread->source_via == App\Thread::PERSON_USER && \Eventy::filter('reply_email.include_signature', true, $thread))
						                                <br>{!! $conversation->getSignatureProcessed(['thread' => $thread]) !!}
						                            @endif
						                            @action('reply_email.after_signature', $thread, $loop, $threads, $conversation, $mailbox)
						                            <br><br>
	                                            </div>
	                                        </div>
						                </td>
						            </tr>
						        </table>
						    </td>
						</tr>
						<tr>
						    <td height="1"><div style="border-bottom:1px solid #e7e7e7;"></div></td>
						</tr>
						<tr>
						    <td height="15"></td>
						</tr>
					@endforeach
					@if (\App\Option::get('email_branding'))
						<tr>
		                    <td height="30" style="font-size:12px; line-height:18px; font-family:Arial,'Helvetica Neue',Helvetica,Tahoma,sans-serif; color: #aaaaaa;">
							{!! __('Support powered by :app_name — Free open source help desk & shared mailbox', ['app_name' => '<a href="'.Config::get('app.freescout_url').'">'.\Config::get('app.name').'</a>']) !!}
							</td>
						</tr>
					@endif
					<tr>
						<td height="0" style="font-size: 0px; line-height: 0px; color:#ffffff;">	                    	
							@if (\App\Option::get('open_tracking'))
								<img src="{{ route('open_tracking.set_read', ['conversation_id' => $threads->first()->conversation_id, 'thread_id' => $threads->first()->id]) }}/" alt="" />
							@endif
							{{-- Addition to Message-ID header to detect relies --}}
							<span style="font-size: 0px; line-height: 0px; color:#ffffff !important;">{{ \MailHelper::getMessageMarker($headers['Message-ID']) }}</span>
						</td>
					</tr>
	            </table>
	        </td>
	    </tr>
	</table>
</body>
</html>