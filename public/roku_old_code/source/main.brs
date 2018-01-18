' ********** Copyright 2015 Roku Corp.  All Rights Reserved. **********

Sub RunUserInterface()
    screen = CreateObject("roSGScreen")
    scene = screen.CreateScene("HomeScene")
    port = CreateObject("roMessagePort")
    screen.SetMessagePort(port)
    screen.Show()

    data = GetApiArray()
    list = []
    scene.gridContent = populateCategories(data)

    while true
        msg = wait(0, port)
        print "------------------"
        print "msg = "; msg
    end while

    if screen <> invalid then
        screen.Close()
        screen = invalid
    end if
End Sub

function populateCategories(xmlData as Object)
    RowItems = createObject("RoSGNode","CustomContentNode")

    for each xmlItem in xmlData
        row = createObject("RoSGNode","CustomContentNode")
        row.title = xmlItem.title
        row.xmlchildren = xmlItem.children
        for each child in xmlItem.children
            item = createObject("RoSGNode","CustomContentNode")
            for each key in child
                item[key] = child[key]
            end for
            item.xmlchildren = child.children
            for each gChild in child.children
                gItem = createObject("RoSGNode","CustomContentNode")
                for each gKey in gChild
                    gItem[gKey] = gChild[gKey]
                end for
                gItem.xmlchildren = gChild.children
                item.streamFormat = "layout"
                item.appendChild(gItem)
            end for
            row.appendChild(item)
        end for
        RowItems.appendChild(row)
    end for

    return RowItems
end function

Function ParseXMLContent(list As Object)
    RowItems = createObject("RoSGNode","CustomContentNode")

    for each rowAA in list
    'for index = 0 to 1
        row = createObject("RoSGNode","CustomContentNode")
        row.Title = rowAA.Title

        for each itemAA in rowAA.ContentList
            item = createObject("RoSGNode","CustomContentNode")
            ' We don't use item.setFields(itemAA) as doesn't cast streamFormat to proper value
            for each key in itemAA
                item[key] = itemAA[key]
            end for
            row.appendChild(item)
        end for
        RowItems.appendChild(row)
    end for

    return RowItems
End Function

function getFeedItems(feedUrl as Object)

    result = []
    url = CreateObject("roUrlTransfer")
    url.SetUrl(feedUrl)
    ?"URL: " + feedUrl
    rsp = url.GetToString()

    responseXML = ParseXML(rsp)
    responseXML = responseXML.GetChildElements()

    for each feed in responseXML
        item = getItemModel()
        item.title = feed.title.getText()
        item.description = feed.description.getText()
        item.HDPosterUrl = feed@hdImg
        if left(item.HDPosterUrl,3) = "/im" then
          item.HDPosterUrl = "http://1stud.io" + item.HDPosterUrl
        end if
        item.streamUrl = feed.media[0].streamUrl.getText()
        item.runtime = feed.runlength.getText()
        item.genres = feed.genres.getText()
        item.starrating = feed.starrating.getText()
        item.stream = {url : feed.media[0].streamUrl.getText()}
        item.url = feed.media[0].streamUrl.getText()
        item.hdBackgroundImageUrl = item.HDPosterUrl
        item.streamFormat = "mp4"
        result.push(item)
    end for
    return result
end function

Function GetApiArray()
    url = CreateObject("roUrlTransfer")
    url.SetUrl("http://1stud.io/tvapp/"+getConfig().channel+"/roku/xml/categories_linear.xml")
    rsp = url.GetToString()

    responseXML = ParseXML(rsp)
    responseXML = responseXML.GetChildElements()
    result = []
    topRow = getItemModel()
    topRow.title = "Featured"
    result.push(topRow)

    for each xmlItem in responseXML
        if xmlItem.getName() = "item"
            itemAA = xmlItem.GetChildElements()
            if itemAA <> invalid
                item = {}
                for each xmlItem in itemAA
                    item[xmlItem.getName()] = xmlItem.getText()
                    if xmlItem.getName() = "media:content"
                        item.stream = {url : xmlItem.url}
                        item.url = xmlItem.getAttributes().url
                        item.streamFormat = "mp4"

                        mediaContent = xmlItem.GetChildElements()
                        for each mediaContentItem in mediaContent
                            if mediaContentItem.getName() = "media:thumbnail"
                                item.HDPosterUrl = mediaContentItem.getattributes().url
                                if left(item.HDPosterUrl,3) = "/im" then
                                    item.HDPosterUrl = "http://1stud.io" + item.HDPosterUrl
                                end if
                                item.hdBackgroundImageUrl = item.HDPosterUrl
                            end if
                        end for
                    end if
                end for
                result.push(item)
            end if
        else if xmlItem.getName() = "category"
            item = getItemModel()
            item.contentId = xmlItem@contentd
            item.title = xmlItem@title
            item.description = xmlItem@description
            item.HDPosterUrl = xmlItem@hd_img
            if left(item.HDPosterUrl,3) = "/im" then
              item.HDPosterUrl = "http://1stud.io" + item.HDPosterUrl
            end if
            item.hdBackgroundImageUrl = item.HDPosterUrl
            item.feed = xmlItem@feed
            item.streamUrl = xmlItem@stream_url
            item.layout = xmlItem@layout

            if item.layout = "linear" and xmlItem.getChildElements() = Invalid and Instr(1, item.feed, "m3u8") = 0 then
                item.children.append(getFeedItems(item.feed))
            end if

            if xmlItem.getChildElements() <> Invalid then
                children = xmlItem.getChildElements()
                for each child in children
                    nestedItem = getItemModel()
                    nestedItem.contentId = child@contentd
                    nestedItem.title = child@title
                    nestedItem.description = child@description
                    nestedItem.HDPosterUrl = child@hd_img
                    if left(nestedItem.HDPosterUrl,3) = "/im" then
                      nestedItem.HDPosterUrl = "http://1stud.io" + nestedItem.HDPosterUrl
                    end if
                    nestedItem.hdBackgroundImageUrl = nestedItem.HDPosterUrl
                    nestedItem.feed = child@feed
                    nestedItem.streamUrl = child@stream_url
                    nestedItem.layout = child@layout
                    if nestedItem.layout = "linear" and child.getChildElements() = Invalid then
                        nestedItem.children.append(getFeedItems(nestedItem.feed))
                    end if
                    item.children.push(nestedItem)
                end for
            end if

            if item.layout = "video" then
                item.children.push(item)
                item.stream = {url : item.streamUrl}
                item.url = item.streamUrl
                if inStr(1,item.streamUrl, "m3u8") > 0 then
                    item.streamFormat = "hls"
                else
                    item.streamFormat = "mp4"
                end if
            end if

            if xmlItem@shelf = "top" then
                item.shelf = xmlItem@shelf
                result[0].children.push(item)
            else
                result.push(item)
            end if
        end if
    end for

    return result
End Function


function getItemModel() as Object
    return {
        contentId: 0,
        title: "",
        description: "",
        HDPosterUrl: "",
        feed: "",
        streamUrl: "",
        layout: "linear",
        shelf: "low",
        children: [],
        runtime: 0,
        genres: "",
        starrating: 0
    }
end function

Function ParseXML(str As String) As dynamic
    if str = invalid return invalid
    xml=CreateObject("roXMLElement")
    if not xml.Parse(str) return invalid
    return xml
End Function
