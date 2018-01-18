' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********
 ' inits grid screen
 ' creates all children
 ' sets all observers
Function Init()
    ? "[LISTSCREEN] Init"

    m.rowList       =   m.top.findNode("RowList")
    m.menutitle = m.top.findNode("menutitle")
    m.listFocusTitle = m.top.findNode("listFocusTitle")
    m.listFocusDescription = m.top.findNode("listFocusDescription")

    m.top.observeField("visible", "onVisibleChange")
    m.top.observeField("focusedChild", "OnFocusedChildChange")
End Function

' handler of focused item in RowList
Sub OnItemFocused()
    itemFocused = m.top.itemFocused

    'When an item gains the key focus, set to a 2-element array,
    'where element 0 contains the index of the focused row,
    'and element 1 contains the index of the focused item in that row.
    If itemFocused.Count() = 2 and itemFocused[0] > -1 and itemFocused[1] > -1 then
        focusedContent          = m.top.content.getChild(itemFocused[0]).getChild(itemFocused[1])
        m.listFocusTitle.text = focusedContent.title
        m.listFocusDescription.text = focusedContent.description
        if focusedContent <> invalid then
            m.top.focusedContent    = focusedContent
        end if
    end if
End Sub

' set proper focus to RowList in case if return from Details Screen
Sub onVisibleChange()
    if m.top.visible = true then
        m.rowList.setFocus(true)
    end if
End Sub

' set proper focus to RowList in case if return from Details Screen
Sub OnFocusedChildChange()
    if m.top.isInFocusChain() and not m.rowList.hasFocus() then
        m.rowList.setFocus(true)
    end if
End Sub
