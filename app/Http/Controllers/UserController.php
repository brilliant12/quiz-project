<?php 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function index()
    {
        // Define cache key
        $cacheKey = 'users_page_' . request()->get('page', 1);

        // Check if the paginated data exists in Redis
        if (Redis::exists($cacheKey)) {
            // Fetch cached data from Redis and decode it into a collection
            $usersData = json_decode(Redis::get($cacheKey), true); // Decode as an array
            $users = collect($usersData); // Convert array into Laravel Collection
           
        } else {
            // If not in Redis, fetch from DB and cache it
            $users = User::orderBy('id')->cursorPaginate(100); // Cursor paginate
          
          
            Redis::setex($cacheKey, 600, json_encode($users->items())); // Cache only the items
        }
        
        return view('users.index', compact('users'));
    }
}
