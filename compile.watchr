# --------------------------------------------------
# Watchr Rules
# --------------------------------------------------
lesses = [
  {'from' => '/home/homepage/suiratnemoc/less/category.less', 'to' => 'public/assets/css/category/category.css'},
]

lesses.each do |i|
  puts "watch #{i['from']}"
  watch ( i['from'] ) {|md|
    cmd = "lessc #{i["from"]} #{i["to"]} -x"
    `#{cmd}`
  	puts "LESS : #{cmd}"
  }
end

coffees = [
  {"from" => 'coffee\/category\/category\.coffee', "to" => "public/assets/js/category.js"},
]

# coffees.each do |j|
  # watch ( 'less/category.less' ) {|md|
    # puts "md"
    # #cmd2 = "coffee -j #{j["to"]} - #{j["from"]}"
    # #`#{cmd2}`
    # #puts "COFFEE : #{cmd2}"
  # }
# end

# --------------------------------------------------
# Signal Handling
# --------------------------------------------------
# Ctrl-\
Signal.trap('QUIT') do
  puts " --- Compiling all .less files ---\n\n"
    Dir['**/*.less'].each {|file| lessc file }
      puts 'all compiled'
      end

# Ctrl-C
Signal.trap('INT')  { abort("\n") }