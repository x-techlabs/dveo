''
''	NWM_MRSS
''	chagedorn@roku.com
''
''	A BrightScript class for parsing standard MRSS files
''	http://video.search.yahoo.com/mrss
''
''	Usage:
''		mrss = NWM_MRSS("http://www.example.com/mrss_feed.xml")	' iniitialize a NWM_MRSS object
''		episodes = mrss.GetEpisodes(10) ' get the first 10 episodes found in the MRSS feed
''		episodes = mrss.GetEpisodes() 	' get all episodes found in the MRSS feed
''

function NWM_MRSS(url)
	this = {
		url:	url
		
		GetEpisodes:	NWM_MRSS_GetEpisodes
	}
	
	return this
end function

' Build an array of content-meta-data objects suitable for passing to roPosterScreen::SetContentList()
function NWM_MRSS_GetEpisodes(limit = 0)
	result = []
	util = NWM_Utilities()
	
	if InStr(1, m.url, "http://") = 1
		raw = NWM_GetStringFromURL(m.url)
	else
		raw = ReadASCIIFile(m.url)
	end if
	'print raw

	xml = CreateObject("roXMLElement")
	if xml.Parse(raw)
		for each item in xml.channel.item
			newItem = {
				streams:			[]
				streamFormat:	"mp4"
				actors:				[]
				categories:		[]
				contentType:	"episode"
			}
			
			' title
			tmp = item.GetNamedElements("media:title")
			if tmp.Count() > 0
				newItem.title = util.HTMLEntityDecode(ValidStr(tmp[0].GetText()))
				newItem.shortDescriptionLine1 = util.HTMLEntityDecode(ValidStr(tmp[0].GetText()))
			else
				newItem.title = util.HTMLEntityDecode(ValidStr(item.title.GetText()))
				newItem.shortDescriptionLine1 = util.HTMLEntityDecode(ValidStr(item.title.GetText()))
			end if
            newItem.ContentId = newItem.title
				
			' description
			if item.GetNamedElements("blip:puredescription").Count() > 0
				tmp = item.GetNamedElements("blip:puredescription")
				description = util.HTMLEntityDecode(util.HTMLStripTags(ValidStr(tmp[0].GetText())))
				newItem.description = description
				newItem.synopsis = description
			else if item.GetNamedElements("media:description").Count() > 0
				tmp = item.GetNamedElements("media:description")
				description = util.HTMLEntityDecode(util.HTMLStripTags(ValidStr(tmp[0].GetText())))
				newItem.description = description
				newItem.synopsis = description
			else
				description = util.HTMLEntityDecode(util.HTMLStripTags(ValidStr(item.description.GetText())))
				newItem.description = description
				newItem.synopsis = description
			end if

			' thumbnail
			tmp = item.GetNamedElements("media:thumbnail")
			if tmp.Count() > 0
				newItem.sdPosterURL = ValidStr(tmp[0]@url)
				newItem.hdPosterURL = ValidStr(tmp[0]@url)
			else if xml.channel.image.url.Count() > 0
				newItem.sdPosterURL = ValidStr(xml.channel.image.url.GetText())
				newItem.hdPosterURL = ValidStr(xml.channel.image.url.GetText())
			end if
				
			' categories
			if item.GetNamedElements("media:category").Count() > 0
				tmp = item.GetNamedElements("media:category")
				for each category in tmp
					newItem.categories.Push(ValidStr(category.GetText()))
				next
			else if item.category.Count() > 0
				for each category in item.category
					newItem.categories.Push(ValidStr(category.GetText()))
				next
			end if
				
			' acrtors and director
			tmp = item.GetNamedElements("media:credit")
			if tmp.Count() > 0
				for each credit in tmp
					if ValidStr(credit@role) = "actor"
						newItem.actors.Push(ValidStr(credit.GetText()))
					else if ValidStr(credit@role) = "director"
						newItem.director = ValidStr(credit.GetText())
					end if
				next
			end if
				
			' rating
			if item.GetNamedElements("media:rating").Count() > 0
				tmp = item.GetNamedElements("media:rating")
				newItem.rating = ValidStr(tmp[0].GetText())
			else if item.GetNamedElements("blip:contentRating").Count() > 0
				tmp = item.GetNamedElements("blip:contentRating")
				newItem.rating = ValidStr(tmp[0].GetText())
			end if

			' release date
			if item.GetNamedElements("blip:datestamp").Count() > 0
				dt = CreateObject("roDateTime")
				dt.FromISO8601String(ValidStr(item.GetNamedElements("blip:datestamp")[0].GetText()))
				newItem.releaseDate = dt.AsDateStringNoParam()
			else
				newItem.releaseDate = ValidStr(item.pubdate.GetText())
			end if
			newItem.shortDescriptionLine2 = newItem.releaseDate
			
			' media:content can be a child of <item> or of <media:group>
			contentItems = item.GetNamedElements("media:content")
			if contentItems.Count() = 0
				tmp = item.GetNamedElements("media:group")
				if tmp.Count() > 0
					contentItems = tmp.GetNamedElements("media:content")
				end if
			end if
			
			' length
			tmp = item.GetNamedElements("blip:runtime")
			if tmp.Count() > 0
				length = StrToI(ValidStr(tmp[0].GetText()))
				if length > 0
					newItem.length = length
				end if
			end if
			
			if contentItems.Count() > 0
				for each content in contentItems
					if ValidStr(content@url) <> ""
						newStream = {
							url:			ValidStr(content@url)
							bitrate:	StrToI(ValidStr(content@bitrate))
						}
						
						' use the content's height attribute to determine HD-ness
						if StrToI(ValidStr(content@height)) > 720
							newStream.quality = true
							newItem.HDBranded = true
							newItem.isHD = true
							newItem.fullHD = true
						else if StrToI(ValidStr(content@height)) > 480
							newStream.quality = true
							newItem.HDBranded = true
							newItem.isHD = true
						end if

						newItem.streams.push(newStream)
					end if
				next
				
				length = StrToI(ValidStr(contentItems[0]@duration))
				if newItem.length = invalid and length > 0
					newItem.length = length
				end if

				'PrintAA(newItem)
				result.Push(newItem)
			else if item.enclosure.Count() > 0
				' we didn't find any media:content tags, try the enclosure tag
				newStream = {
					url:	ValidStr(item.enclosure@url)
				}
				
				newItem.streams.Push(newStream)

				'PrintAA(newItem)
				result.Push(newItem)
			end if
		next
	end if
	
	return result
end function
