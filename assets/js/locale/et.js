$.fullCalendar.locale("et", {
	buttonText: {
		month: "Kuu",
		week: "Nädal",
		day: "Päev",
		list: "Päevakord",
		listWeek: 'Päevakord',
	},
	allDayText: "Kogu päev",
	eventLimitText: function(n) {
		return "+ veel " + n;
	},
	noEventsMessage: "Kuvamiseks puuduvad sündmused"
});
