Function TrackVideoStart(show, settings)

    did$ = GetDeviceESN() + "|" + show.contentid + "|" + show.contenttype + "|" + settings.channel_id
    url = "http://1stud.io/track_start/?id=" + StrToHex(did$)
    print url

    http = NewHttp(url)
    rsp = http.GetToStringWithRetry()

    return rsp
End Function

Function TrackVideoEnd(tid$)
    url = "http://1stud.io/track_end/?id=" + tid$
    print url

    http = NewHttp(url)
    rsp = http.GetToStringWithRetry()
    return rsp
End Function

Function displayVideo(show, settings, format$)

    videourl = show.StreamUrls[0]
    'print "Displaying video:  url is "	 videourl

    if videourl <> invalid 
        trackerID = TrackVideoStart(show, settings)
        timer = CreateObject("roTimespan")
        timer.Mark()

    	videoplayer = CreateObject("roVideoscreen")
    	port = CreateObject("roMessagePort")
    	videoplayer.setMessagePort(port)

        rovideoAssoContent = {}
        rovideoAssoContent = { Streamurls: [videourl]
    		 				   StreamFormat: format$
    		 				   StreamQualities : ["SD"]
    		 				   SwitchingStrategy: "full-adaptation"
    		 				   StreamBitrates : 0 
    		 				   Live: True   				
        			        }
                            
        videoplayer.SetContent(rovideoAssoContent)    	
        videoplayer.SetPositionNotificationPeriod(1)
        videoplayer.show() 

         while true
             msg = wait(0, videoplayer.GetMessagePort())

             if (timer.TotalSeconds() > 60) then
                 TrackVideoEnd(trackerID)
                 timer.Mark()
             endif  

             if type(msg) = "roVideoScreenEvent"
                 if msg.isScreenClosed() then 'ScreenClosed event
                     print "Closing video screen"
                    exit while
                 else if msg.isPlaybackPosition() then
                     nowpos = msg.GetIndex()
                     print "playback position is "nowpos                     
                    
                 else if msg.isRequestFailed()
                     print "play failed: "; msg.GetMessage()
                 else
                     print "Unknown event: "; msg.GetType(); " msg: "; msg.GetMessage()
                 endif
             end if
         end while

         videoplayer.close()
         TrackVideoEnd(trackerID)
     else 
         print " Invalid values passed for videourl and title in displayvideo Function" 
     end if       
End Function


Function displayVideoLive(show, settings, format$)
    videourl = show.streamurl
    print "Displaying video:  url is "   videourl

    if videourl <> invalid 
        trackerID = TrackVideoStart(show, settings)
        timer = CreateObject("roTimespan")
        timer.Mark()

        videoplayer = CreateObject("roVideoscreen")
        port = CreateObject("roMessagePort")
        videoplayer.setMessagePort(port)

        rovideoAssoContent = {}
        rovideoAssoContent = {  Streamurls: [videourl]
                                StreamFormat: format$
                                StreamQualities : ["SD"]
                                SwitchingStrategy: "full-adaptation"
                                StreamBitrates : 0 
                                Live: True                  
                            }
                            
        videoplayer.SetContent(rovideoAssoContent)      
        videoplayer.SetPositionNotificationPeriod(1)
        videoplayer.show() 

         while true
             msg = wait(0, videoplayer.GetMessagePort())

             if (timer.TotalSeconds() > 60) then
                 TrackVideoEnd(trackerID)
                 timer.Mark()
             endif  

             if type(msg) = "roVideoScreenEvent"
                 if msg.isScreenClosed() then 'ScreenClosed event
                     print "Closing video screen"
                     'Main()
                    exit while
                 else if msg.isPlaybackPosition() then
                     nowpos = msg.GetIndex()
                     print "playback position is "nowpos                     
                    
                 else if msg.isRequestFailed()
                     print "play failed: "; msg.GetMessage()
                 else
                     print "Unknown event: "; msg.GetType(); " msg: "; msg.GetMessage()
                 endif
             end if
         end while

         videoplayer.close()
         TrackVideoEnd(trackerID)

     else 
         print " Invalid values passed for videourl and title in displayvideo Function" 
     end if       
End Function
