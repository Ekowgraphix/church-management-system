<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YouTubeController extends Controller
{
    /**
     * Search YouTube videos
     */
    public function search(Request $request)
    {
        $apiKey = config('services.youtube.key');
        $query = $request->input('q', 'worship songs');
        $maxResults = $request->input('max', 12);

        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'key' => $apiKey,
                'part' => 'snippet',
                'q' => $query,
                'maxResults' => $maxResults,
                'type' => 'video',
                'order' => 'relevance'
            ]);

            if ($response->successful()) {
                $videos = $response->json()['items'] ?? [];
                return view('youtube.search', compact('videos', 'query'));
            } else {
                return view('youtube.search', [
                    'videos' => [],
                    'query' => $query,
                    'error' => 'Failed to fetch videos. Please check your API key.'
                ]);
            }
        } catch (\Exception $e) {
            return view('youtube.search', [
                'videos' => [],
                'query' => $query,
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show video details
     */
    public function show($videoId)
    {
        $apiKey = config('services.youtube.key');

        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'key' => $apiKey,
                'part' => 'snippet,statistics,contentDetails',
                'id' => $videoId
            ]);

            if ($response->successful()) {
                $video = $response->json()['items'][0] ?? null;
                
                if ($video) {
                    return view('youtube.show', compact('video'));
                }
            }
            
            return redirect()->route('media.youtube.search')->with('error', 'Video not found');
        } catch (\Exception $e) {
            return redirect()->route('media.youtube.search')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get channel videos (for church's YouTube channel)
     */
    public function channel(Request $request)
    {
        $apiKey = config('services.youtube.key');
        $channelId = $request->input('channel_id', env('YOUTUBE_CHANNEL_ID'));
        $maxResults = $request->input('max', 12);

        if (!$channelId) {
            return view('youtube.channel', [
                'videos' => [],
                'error' => 'Please set YOUTUBE_CHANNEL_ID in your .env file'
            ]);
        }

        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'key' => $apiKey,
                'part' => 'snippet',
                'channelId' => $channelId,
                'maxResults' => $maxResults,
                'order' => 'date',
                'type' => 'video'
            ]);

            if ($response->successful()) {
                $videos = $response->json()['items'] ?? [];
                return view('youtube.channel', compact('videos'));
            } else {
                return view('youtube.channel', [
                    'videos' => [],
                    'error' => 'Failed to fetch channel videos.'
                ]);
            }
        } catch (\Exception $e) {
            return view('youtube.channel', [
                'videos' => [],
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
