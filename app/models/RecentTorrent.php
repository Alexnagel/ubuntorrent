<?php

class RecentTorrent extends Eloquent {
    
    protected $table    = 'recent_torrents';
    protected $fillable = array(
                                'name',
                                'title',
                                'date_added',
                            );
}