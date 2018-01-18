<?php

use Illuminate\Database\Seeder;

class TvappPlatformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [ 'id' => 1, 'slug' => 'web',           'title' => 'Mobile/Web TV'],
            [ 'id' => 2, 'slug' => 'roku',          'title' => 'Roku'],
            [ 'id' => 3, 'slug' => 'firetv',        'title' => 'Fire TV'],
            [ 'id' => 4, 'slug' => 'appletv',       'title' => 'Apple TV'],
        ];

        foreach ($items as $data){
            $item = TvappPlatform::find($data['id']);
            if(is_null($item)){ $item = new TvappPlatform; }
            $item->id = $data['id'];
            $item->slug = $data['slug'];
            $item->title = $data['title'];
            $item->save();
        }

        while(TvappPlaylist::has('platforms','=', '0')->count() > 0) {
            $playlists = TvappPlaylist::has('platforms','=', '0')->limit(100)->get();
            foreach ($playlists as $playlist) {
                $playlist->platforms()->sync([1, 2, 3, 4]);
            }
        }
    }
}
