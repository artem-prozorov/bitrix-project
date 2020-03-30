{"version":3,"sources":["calendar-view-custom.js"],"names":["window","View","BXEventCalendarView","CustomView","calendar","customViewDefinition","apply","this","arguments","appView","name","ID","type","placementCode","util","config","placementParams","gridPlacementCode","title","TITLE","APP_NAME","BX","message","contClassName","preBuild","prototype","Object","create","constructor","viewCont","props","className","style","display","MessageInterface","rest","AppLayout","initializePlacement","getEvents","proxy","appGetEntries","viewEvent","appViewEvent","addEvent","appAddEvent","editEvent","appEditEvent","deleteEvent","appDeleteEvent","events","push","build","titleCont","appendChild","appWrap","height","getViewHeight","adjustViewRangeToDate","date","currentViewName","isBuilt","show","appLoader","adjust","getLoader","viewRange","getDisplayedViewRange","appRequestIsRunning","ajax","url","serviceUrl","method","dataType","data","LOADER_ID","Math","round","random","PARAMS","params","APP_ID","PLACEMENT","PLACEMENT_ID","PLACEMENT_OPTIONS","viewRangeFrom","format","start","getViewRangeDate","viewRangeTo","end","onsuccess","delegate","appRequestOnSuccess","onfailure","appRequestOnFailure","onCustomEvent","html","remove","then","defer","appLayout","get","allowInterface","innerHTML","replace","callback","dateFrom","Date","dateTo","getFullYear","getMonth","getDate","setHours","setDisplayedViewRange","entries","entryController","getList","startDate","finishDate","finishCallback","forEach","entry","UID","getUniqueId","isArray","i","length","entriesIndex","uid","getEntryByParams","id","getEntryById","showViewSlider","result","showEditSlider","editEntry","deleteEntry","displayEntries","decreaseViewRangeDate","increaseViewRangeDate","BXEventCalendar","CalendarCustomView","addCustomEvent"],"mappings":"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","file":""}