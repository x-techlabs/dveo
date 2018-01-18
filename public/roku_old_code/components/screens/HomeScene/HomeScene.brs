' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********
 ' inits grid screen
 ' creates all children
 ' sets all observers
Function Init()
    ' listen on port 8089
    ? "[HomeScene] Init"

    ' GridScreen node with RowList
    m.gridScreen = m.top.findNode("GridScreen")
    m.listScreenContent = []

    ' DetailsScreen Node with description, Video Player
    m.detailsScreen = m.top.findNode("DetailsScreen")
    m.listScreen = m.top.findNode("ListScreen")

    ' Observer to handle Item selection on RowList inside GridScreen (alias="GridScreen.rowItemSelected")
    m.top.observeField("rowItemSelected", "OnRowItemSelected")
    m.top.observeField("listItemSelected", "OnListItemSelected")

    ' loading indicator starts at initializatio of channel
    m.loadingIndicator = m.top.findNode("loadingIndicator")
    m.listScreenFocused = false
End Function

' if content set, focus on GridScreen
Function OnChangeContent()
    m.gridScreen.setFocus(true)
    m.loadingIndicator.control = "stop"
End Function

Function OnListContent()
    m.listScreen.setFocus(true)
End Function

' Row item selected handler
Function OnRowItemSelected()
    ' On select any item on home scene, show Details node and hide Grid
    m.gridScreen.visible = "false"
    currentItem = m.gridScreen.focusedContent

    if currentItem.url <> Invalid and Instr(1, currentItem.url,"m3u8") > 0 then
        currentItem.streamFormat = "hls"
        m.detailsScreen.content = m.gridScreen.focusedContent
        m.detailsScreen.setFocus(true)
        m.detailsScreen.visible = "true"
        m.listScreenFocused = false
    else if currentItem.streamFormat = "mp4" then
        m.detailsScreen.content = m.gridScreen.focusedContent
        m.detailsScreen.setFocus(true)
        m.detailsScreen.visible = "true"
        m.listScreenFocused = false
    else
        lcontent = createObject("RoSGNode", "CustomContentNode")
        tempItem = createObject("RoSGNode", "CustomContentNode")
        for each key in currentItem
            tempItem[key] = currentItem[key]
        end for
        tempItem.xmlchildren = []
        tempItem.xmlchildren.append(currentItem.xmlchildren)
        for each child in currentItem.xmlchildren
            childItem = createObject("RoSGNode","CustomContentNode")
            for each key in child
              childItem[key] = child[key]
            end for
            childItem.xmlchildren = child.children
            tempItem.appendChild(childItem)
        end for
        lcontent.appendChild(tempItem)
        m.listScreen.title = currentItem.title
        m.listScreen.content = lcontent
        m.listScreen.visible = "true"
        m.listScreen.setFocus(true)
        m.listScreenFocused = true
    end if
End Function

Function OnListItemSelected()
    ' On select any item on home scene, show Details node and hide Grid
    m.listScreen.visible = "false"
    currentItem = m.listScreen.focusedContent
    m.listScreenContent.push(m.listScreen.content)
    stop
    if currentItem.streamFormat = "mp4" then
        m.detailsScreen.content = m.listScreen.focusedContent
        m.detailsScreen.setFocus(true)
        m.detailsScreen.visible = "true"
        m.listScreenFocused = true
    else
        lcontent = createObject("RoSGNode", "CustomContentNode")
        tempItem = createObject("RoSGNode", "CustomContentNode")
        for each key in currentItem
            tempItem[key] = currentItem[key]
        end for
        tempItem.xmlchildren = []
        tempItem.xmlchildren.append(currentItem.xmlchildren)
        for each child in currentItem.xmlchildren
            childItem = createObject("RoSGNode","CustomContentNode")
            for each key in child
              childItem[key] = child[key]
            end for
            tempItem.appendChild(childItem)
        end for
        lcontent.appendChild(tempItem)
        m.listScreen.title = currentItem.title
        m.listScreen.content = lcontent
        m.listScreen.visible = "true"
        m.listScreen.setFocus(true)
        m.listScreenFocused = true
    end if
End Function

' Main Remote keypress event loop
Function OnKeyEvent(key, press) as Boolean
    ? ">>> HomeScene >> OnkeyEvent"
    result = false
    if press then
        if key = "options"
            ' option key handler
        else if key = "back"

            ' if Details opened
            if m.detailsScreen.videoPlayerVisible = true then
                m.detailsScreen.videoPlayerVisible = "false"
                result = true

            else if m.gridScreen.visible = false and m.listScreen.visible = true then
                if m.listScreenContent.count() > 0 then
                  lastContent = m.listScreenContent.pop()
                  m.listScreen.content = lastContent
                else
                  m.listScreen.visible = "false"
                  m.gridScreen.visible = "true"
                  m.gridScreen.setFocus(true)
                end if
                result = true

            else if m.gridScreen.visible = false and m.detailsScreen.visible = true then
                m.detailsScreen.visible = "false"

                if m.listScreenFocused = false then
                    m.listScreen.visible = "false"
                    m.gridScreen.visible = "true"
                    m.gridScreen.setFocus(true)
                else
                    m.listScreen.visible = "true"
                    m.gridScreen.visible = "false"
                    m.listScreen.setFocus(true)
                end if
                result = true

            end if

        end if
    end if
    return result
End Function
