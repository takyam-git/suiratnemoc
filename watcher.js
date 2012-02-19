var fs = require('fs')
	, exec = require('child_process').exec;

var coffee_scripts = [
	{ from: 'coffee/calendar/*.coffee', to: 'public/assets/js/calendar.js' },
	{ from: 'coffee/category/*.coffee', to: 'public/assets/js/category.js' },
];
var less_files = [
	{ from: 'less/category.less', to: 'public/assets/css/category/category.css'},
	{ from: 'less/calendar.less', to: 'public/assets/css/calendar/calendar.css'},
	{ from: 'less/colors.less', to: 'public/assets/css/colors.css'},
];

var execCommand = function(command){
	console.log('SET COMMAND >> ' + command);
	exec(command, function(error, stdout, stderr){
		if(error){ console.error(error); }
		if(stdout){ console.log(stdout); }
		if(stderr){ console.error(stderr); }
	});
}

console.log('START FILE WATCHING');
coffee_scripts.forEach(function(coffee){
	var command = 'coffee -j ' + __dirname + '/' + coffee.to + ' -c ' + __dirname + '/' + coffee.from;
	fs.watchFile(coffee.from.replace(/\*\.coffee$/, ''), function(current,prev){
		if(current.mtime > prev.mtime){
			execCommand(command);
		}
	});
});

less_files.forEach(function(less){
	var command = 'lessc -x ' + __dirname + '/' + less.from + ' ' + __dirname + '/' + less.to;
	fs.watchFile(less.from, function(current,prev){
		if(current.mtime > prev.mtime){
			console.log(command);
			execCommand(command);
		}
	});
});
