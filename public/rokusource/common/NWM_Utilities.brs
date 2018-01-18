''
''	NWM_Utilities.brs
''	chagedorn@roku.com
''
''

function NWM_Utilities()
	this = {
		GetStringFromURL:	NWM_UT_GetStringFromURL
		HTMLEntityDecode:	NWM_UT_HTMLEntityDecode
		HTMLStripTags:		NWM_UT_HTMLStripTags
	}
	
	return this
end function

function NWM_UT_GetStringFromURL(url, userAgent = "")
	result = ""
	timeout = 10000
	
  ut = CreateObject("roURLTransfer")
  ut.SetPort(CreateObject("roMessagePort"))
  if userAgent <> ""
	  ut.AddHeader("user-agent", userAgent)
	end if
  ut.SetURL(url)
	if ut.AsyncGetToString()
		event = wait(timeout, ut.GetPort())
		if type(event) = "roUrlEvent"
				print ValidStr(event.GetResponseCode())
				result = event.GetString()
				'exit while        
		elseif event = invalid
				ut.AsyncCancel()
				REM reset the connection on timeouts
				'ut = CreateURLTransferObject(url)
				'timeout = 2 * timeout
		else
				print "roUrlTransfer::AsyncGetToString(): unknown event"
		endif
	end if
	
	return result
end function

function NWM_UT_HTMLEntityDecode(inStr)
	result = inStr
	
	rx = CreateObject("roRegEx", "&(#39|#8217);", "")
	result = rx.ReplaceAll(result, "'")

	rx = CreateObject("roRegEx", "&amp;", "")
	result = rx.ReplaceAll(result, "&")

	rx = CreateObject("roRegEx", "&(quot|rsquo|lsquo|#8220|#8221);", "")
	result = rx.ReplaceAll(result, Chr(34))
	
	rx = CreateObject("roRegEx", "&\w+;", "")
	result = rx.ReplaceAll(result, "")
	
	return result
end function

function NWM_UT_HTMLStripTags(inStr)
	result = inStr
	
	rx = CreateObject("roRegEx", "<.*?>", "")
	result = rx.ReplaceAll(result, "")

	return result
end function

Function NWM_GetStringFromURL(url) as Dynamic
	result = ""
	timeout = 10000
	
  ut = CreateObject("roURLTransfer")
  ut.SetPort(CreateObject("roMessagePort"))
  ut.AddHeader("user-agent", "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543 Safari/419.3")
  ut.SetURL(url)
	if ut.AsyncGetToString()
		event = wait(timeout, ut.GetPort())
		if type(event) = "roUrlEvent"
				print ValidStr(event.GetResponseCode())
				result = event.GetString()
				'exit while        
		elseif event = invalid
				ut.AsyncCancel()
				REM reset the connection on timeouts
				'ut = CreateURLTransferObject(url)
				'timeout = 2 * timeout
		else
				print "roUrlTransfer::AsyncGetToString(): unknown event"
		endif
	end if
	
	return result
End Function

function NWM_ResolveRedirect(url)
	result = url
	done = false
	
	ut = CreateObject("roURLTransfer")
	ut.SetPort(CreateObject("roMessagePort"))
  ut.AddHeader("user-agent", "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543 Safari/419.3")
	while not done
		ut.SetURL(result)
	
		if ut.AsyncHead()
			while true
				msg = wait(10000, ut.GetPort())
				
				if msg <> invalid
					h = msg.GetResponseHeaders()
					PrintAA(h)
					if ValidStr(h.location) <> ""
						result = ValidStr(h.location)
					else
						done = true
					end if
				else
					done = true
				end if
				exit while
			end while
		else 
			done = true
		end if
	end while
	
	return result
end function
