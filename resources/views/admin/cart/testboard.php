
//for article
$articlescount = DB::table('article')->count();
$lastarticlescount = DB::table('users')->where(["id"=>$userid])->select('lastarticlescount')->get()[0];
$articlediff = $articlescount - $lastarticlescount->lastarticlescount;
