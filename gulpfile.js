var elixir = require('laravel-elixir');
require('laravel-elixir-sass-compass');

elixir(function(mix) {

 mix.compass("styles.scss", "public/css", {
  style: "compressed",
  sass: "public/loading",
  sourcemap: true
 });
});
