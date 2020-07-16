<div class="{{ $showAsModal ? 'modal fade' : '' }}" id="userAdModal" tabindex="-1" role="dialog" aria-labelledby="userAdLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        @if($userAd)

            @php

                $content = unserialize($userAd->content);
                $title = $content['header_title'];
                $appreciationMsg = $content['appreciation_msg'];
                $btn_1 = $content['first_btn'];
                $btn_2 = $content['second_btn'];
                $paypal_donation = $content['paypal_donation'];
                $paypal = $user->paypal;
                $imgUrl = $content['image_url'];
                $imgPath = $content['upload'];
                $adUrl = $content['ad_url'];

                // check the user links to show links button
                
                $link_1 = $user->userlinks()->where('name', $btn_1)->first();
                $link_2 = $user->userlinks()->where('name', $btn_2)->first();

            @endphp

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title ?? 'Thank you from ' . $user->username . '!' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    @if($btn_1 || $btn_2)
                        <div class="row no-gutters justify-content-center pb-3">
                            @if(($btn_1 && $btn_2) && ($link_1 && $link_2))
                                <div class="col text-center">
                                    {!! UserLink::userLinkIcon($link_1->name, $link_1->url) !!}
                                </div>
                                <div class="col text-center">
                                    {!! UserLink::userLinkIcon($link_2->name, $link_2->url) !!}
                                </div>
                            @elseif (($btn_1 xor $btn_2) || ($link_1 || $link_2))
                                @php
                                    $btn = $btn_1 ?? $btn_2;
                                    $link = $link_1 ?? $link_2;
                                @endphp
                                <div class="col text-center">
                                    {!! UserLink::userLinkIcon($link->name, $link->url) !!}
                                </div>
                            @endif
                        </div>
                    @endif
                    @if($appreciationMsg)
                        <div class="row">
                            <div class="col text-center">
                                <div class="py-3">
                                    <h5><strong>{{ $appreciationMsg }}</strong></h5>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($paypal_donation)
                        <div class="row justify-content-center py-3">
                            <div class="col text-center">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                    <input type="hidden" name="cmd" value="_donations" />
                                    <input type="hidden" name="business" value="{{ $paypal }}" />
                                    <input type="hidden" name="currency_code" value="USD" />
                                    <button type="submit" class="btn btn-outline-custom-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px" xml:space="preserve">
                                            <path style="fill:#002987;" d="M428.876,132.28c0.867-7.045,1.32-14.218,1.32-21.497C430.196,49.6,380.597,0,319.413,0H134.271  c-11.646,0-21.589,8.41-23.521,19.894l-68.22,405.475c-2.448,14.55,8.768,27.809,23.521,27.809h67.711  c11.646,0,21.776-8.404,23.707-19.889c0,0,0.113-0.673,0.317-1.885h0.001l-9.436,56.086C146.195,500.313,156.08,512,169.083,512  h59.237c10.265,0,19.029-7.413,20.731-17.535l16.829-100.02c2.901-17.242,17.828-29.867,35.311-29.867h15.562  c84.53,0,153.054-68.525,153.054-153.054C469.807,178.815,453.639,149.902,428.876,132.28z"></path>
                                            <path style="fill:#0085CC;" d="M428.876,132.28c-10.594,86.179-84.044,152.91-173.086,152.91h-51.665  c-11.661,0-21.732,7.767-24.891,18.749l-30.882,183.549C146.195,500.312,156.08,512,169.083,512h59.237  c10.265,0,19.029-7.413,20.731-17.535l16.829-100.02c2.901-17.242,17.828-29.867,35.311-29.867h15.562  c84.53,0,153.054-68.525,153.054-153.054l0,0C469.807,178.815,453.639,149.902,428.876,132.28z"></path>
                                            <path style="fill:#00186A;" d="M204.125,285.19h51.665c89.043,0,162.493-66.731,173.086-152.909  c-15.888-11.306-35.304-17.978-56.29-17.978h-134.85c-15.353,0-28.462,11.087-31.01,26.227l-27.493,163.408  C182.392,292.956,192.464,285.19,204.125,285.19z"></path>
                                        </svg>  Donate
                                    </button>
                                    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                                </form>
                            </div>
                        </div>
                    @endif
                    @if($imgUrl || $imgPath)
                        @php
                            $img = $imgUrl ?? $imgPath;
                            $url = $adUrl ?? '#';
                        @endphp
                        
                        <div class="row">
                            <div class="col text-center">
                                <a href="{{ $url }}" target="_blank">
                                    <img style="width: 100%" src="{{ $img }}">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            
            @php    

                $externalAd = '';
                $externalAdHasScript = false;
                if($showAsModal){
                    $externalAd = ExternalAdInjector::getAd('download');
                    if(strpos($externalAd, '<script>') !== FALSE){
                        $externalAdHasScript = true;
                    }                
                }

            @endphp

            @if($showAsModal && $externalAdHasScript)
                @push('footer_scripts')
                    {!! $externalAd !!}
                @endpush
            @endif

            <div class="modal-content">
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col text-center">
                            @if($showAsModal && !$externalAdHasScript)
                                {!! $externalAd !!}
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        @endif
    </div>
</div>