<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Modules\Order\app\Models\Order;
use Illuminate\Notifications\Notifiable;
use Modules\LiveChat\app\Models\Message;
use Modules\Property\app\Models\Property;
use Modules\Property\app\Models\Wishlist;
use Modules\Property\app\Models\PropertyReview;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'status',
        'is_banned',
        'verification_token',
        'forget_password_token',
        'facebook',
        'linkedin',
        'twitter',
        'website',
        'about',
        'phone',
        'address',
        'image',
        'designation',
        'slug',
        'whatsapp_number',
        'profession_start',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function contactUsers()
    {
        return User::whereIn('id', $this->messagesSent()->pluck('receiver_id'))
            ->orWhereIn('id', $this->messagesReceived()->pluck('sender_id'))
            ->get();
    }

    public function contactUsersWithUnseenMessages()
    {
        $contactUsers = User::whereIn('id', $this->messagesSent()->pluck('receiver_id'))
            ->orWhereIn('id', $this->messagesReceived()->pluck('sender_id'))
            ->select('id', 'name', 'email', 'image')
            ->get();

        $contactUsersWithUnseenMessages = [];

        foreach ($contactUsers as $contactUser) {
            $unseenMessagesCount = Message::where('sender_id', $contactUser->id)
                ->where('receiver_id', $this->id)
                ->where('seen', 'no')
                ->count();

            $lastMessage = Message::where(function ($query) use ($contactUser) {
                $query->where('sender_id', $this->id)->where('receiver_id', $contactUser->id);
            })->orWhere(function ($query) use ($contactUser) {
                $query->where('sender_id', $contactUser->id)->where('receiver_id', $this->id);
            })->latest('created_at')->first();

            $contactUsersWithUnseenMessages[] = (object) [
                'id' => $contactUser->id,
                'name' => $contactUser->name,
                'email' => $contactUser->email,
                'image' => $contactUser->image,
                'new_message' => $unseenMessagesCount,
                'last_message' => $lastMessage->created_at,
            ];
        }

        usort($contactUsersWithUnseenMessages, function ($a, $b) {
            return $b->last_message <=> $a->last_message;
        });

        return $contactUsersWithUnseenMessages;
    }

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', UserStatus::DEACTIVE);
    }

    public function scopeBanned($query)
    {
        return $query->where('is_banned', UserStatus::BANNED);
    }

    public function scopeUnbanned($query)
    {
        return $query->where('is_banned', UserStatus::UNBANNED);
    }

    public function socialite()
    {
        return $this->hasMany(SocialiteCredential::class, 'user_id');
    }

    public function scopeVerify($query)
    {
        return $query->where('email_verified_at', '!=', null);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PropertyReview::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function getImageUrlAttribute()
    {;
        $setting = cache('setting');
        $value = $this->image;

        // check if file is exists
        if ($value && !file_exists(public_path($value))) {
            if (str_contains($value, 'https:/')) {
                $value = $value;
            } else {
                $value = $this->media?->path;
                if ($value) {
                    $value = asset($value);
                }
            }
        } else if ($value) {
            $value = asset($value);
        }
        return $value ? $value : asset($setting->default_avatar);
    }
}
