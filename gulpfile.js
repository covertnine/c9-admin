const gulp = require("gulp");
const zip = require("gulp-zip");
const rename = require('gulp-rename');
const info = require("./package.json");

// Bundle into bundled/c9-admin-dashboard.zip
const compress = function () {
	return gulp.src([
			"**/*",
			"!node_modules{,/**}",
			"!bundled{,/**}",
			"!.git{,/**}",
		], {dot: true})
		// Root of the zip should be a single dir with the plugin name
		.pipe(rename( file => file.dirname = `${info.name}/${file.dirname}` ))
		.pipe(zip(`${info.name}.zip`))
		.pipe(gulp.dest('bundled'));
};

gulp.task("compress", compress);
