<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public $quotes = [
        ["quote" => "It does not matter how slowly you go as long as you do not stop.", "author" => "Confucius"],
        ["quote" => "Life is what happens when you’re busy making other plans.", "author" => "John Lennon"],
        ["quote" => "The future belongs to those who believe in the beauty of their dreams.", "author" => "Eleanor Roosevelt"],
        ["quote" => "In the middle of difficulty lies opportunity.", "author" => "Albert Einstein"],
        ["quote" => "Keep your face always toward the sunshine—and shadows will fall behind you.", "author" => "Walt Whitman"],
        ["quote" => "You miss 100% of the shots you don’t take.", "author" => "Wayne Gretzky"],
        ["quote" => "Whether you think you can or you think you can’t, you’re right.", "author" => "Henry Ford"],
        ["quote" => "It always seems impossible until it’s done.", "author" => "Nelson Mandela"],
        ["quote" => "If you look at what you have in life, you’ll always have more.", "author" => "Oprah Winfrey"],
        ["quote" => "Strive not to be a success, but rather to be of value.", "author" => "Albert Einstein"],
        ["quote" => "I attribute my success to this: I never gave or took any excuse.", "author" => "Florence Nightingale"],
        ["quote" => "You must be the change you wish to see in the world.", "author" => "Mahatma Gandhi"],
        ["quote" => "In three words I can sum up everything I've learned about life: it goes on.", "author" => "Robert Frost"],
        ["quote" => "Fall seven times and stand up eight.", "author" => "Japanese Proverb"],
        ["quote" => "Success is not final, failure is not fatal: It is the courage to continue that counts.", "author" => "Winston Churchill"],
        ["quote" => "Hardships often prepare ordinary people for an extraordinary destiny.", "author" => "C.S. Lewis"],
        ["quote" => "Believe you can and you’re halfway there.", "author" => "Theodore Roosevelt"],
        ["quote" => "Happiness is not something readymade; it comes from your own actions.", "author" => "Dalai Lama"],
        ["quote" => "Whatever you are, be a good one.", "author" => "Abraham Lincoln"],
        ["quote" => "Your time is limited, so don’t waste it living someone else’s life.", "author" => "Steve Jobs"],
        ["quote" => "The only person you are destined to become is the person you decide to be.", "author" => "Ralph Waldo Emerson"],
        ["quote" => "We can’t help everyone, but everyone can help someone.", "author" => "Ronald Reagan"],
        ["quote" => "Start where you are. Use what you have. Do what you can.", "author" => "Arthur Ashe"],
        ["quote" => "If you’re going through hell, keep going.", "author" => "Winston Churchill"],
        ["quote" => "Everything has beauty, but not everyone can see.", "author" => "Confucius"],
        ["quote" => "It’s not whether you get knocked down, it’s whether you get up.", "author" => "Vince Lombardi"],
        ["quote" => "The best way to predict your future is to create it.", "author" => "Peter Drucker"],
        ["quote" => "Life isn’t about finding yourself. Life is about creating yourself.", "author" => "George Bernard Shaw"],
        ["quote" => "If you tell the truth, you don’t have to remember anything.", "author" => "Mark Twain"],
        ["quote" => "Change your thoughts and you change your world.", "author" => "Norman Vincent Peale"]
    ];

    public function getQuote()
    {
        $randomKey = array_rand($this->quotes);

        return response()
            ->json($this->quotes[$randomKey]);
    }
}
