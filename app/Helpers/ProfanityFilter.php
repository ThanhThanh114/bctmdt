<?php

namespace App\Helpers;

class ProfanityFilter
{
    /**
     * Danh sách từ ngữ nhạy cảm / chửi thề (tiếng Việt & tiếng Anh)
     */
    private static $profanityWords = [
        // Tiếng Việt
        'đm',
        'dm',
        'đmm',
        'dmm',
        'đụ',
        'du',
        'địt',
        'dit',
        'lồn',
        'lon',
        'buồi',
        'buoi',
        'cặc',
        'cac',
        'vãi',
        'vai',
        'đéo',
        'deo',
        'cc',
        'vcl',
        'vl',
        'clgt',
        'clmm',
        'đkm',
        'dkm',
        'đcm',
        'dcm',
        'đcmm',
        'dcmm',
        'đm mẹ',
        'dm me',
        'đụ má',
        'du ma',
        'đĩ',
        'di thoi',
        'đĩ thõa',
        'chó',
        'cho',
        'súc vật',
        'suc vat',
        'ngu',
        'ngốc',
        'ngoc',
        'khốn',
        'khon',
        'thối',
        'thoi',
        'rác rưởi',
        'rac ruoi',
        'phá hoại',
        'pha hoai',
        'lừa đảo',
        'lua dao',
        'lừa gát',
        'lua gat',
        'cút',
        'cut',
        'cút xéo',
        'cut xeo',
        'chết',
        'chet',
        'chết tiệt',
        'chet tiet',
        'con chó',
        'con cho',
        'đồ chó',
        'do cho',
        'đồ ngu',
        'do ngu',
        'khùng',
        'khung',
        'điên',
        'dien',
        'ngu xuẩn',
        'ngu xuan',
        'chó má',
        'cho ma',
        'súc sinh',
        'suc sinh',
        'phản bội',
        'phan boi',
        'lừa lọc',
        'lua loc',
        'bẩn thỉu',
        'ban thiu',
        'hèn hạ',
        'hen ha',
        'đểu',
        'deu',

        // Tiếng Anh
        'fuck',
        'shit',
        'bitch',
        'asshole',
        'damn',
        'bastard',
        'crap',
        'dick',
        'pussy',
        'cock',
        'motherfucker',
        'ass',
        'hell',
        'stupid',
        'idiot',
        'dumb',
        'suck',
        'wtf',
    ];

    /**
     * Kiểm tra và lọc nội dung nhạy cảm
     * 
     * @param string $text
     * @return string
     */
    public static function filter($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Normalize text for comparison
        $normalizedText = self::normalizeVietnamese($text);

        // Check each profanity word
        foreach (self::$profanityWords as $badWord) {
            // Create pattern to match the word with word boundaries
            $pattern = '/\b' . preg_quote($badWord, '/') . '\b/iu';

            // Replace with asterisks
            if (preg_match($pattern, $normalizedText)) {
                $replacement = str_repeat('*', mb_strlen($badWord));
                $text = preg_replace($pattern, $replacement, $text);
                $normalizedText = preg_replace($pattern, $replacement, $normalizedText);
            }
        }

        return $text;
    }

    /**
     * Kiểm tra xem text có chứa từ nhạy cảm không
     * 
     * @param string $text
     * @return bool
     */
    public static function containsProfanity($text)
    {
        if (empty($text)) {
            return false;
        }

        $normalizedText = self::normalizeVietnamese(strtolower($text));

        foreach (self::$profanityWords as $badWord) {
            $pattern = '/\b' . preg_quote($badWord, '/') . '\b/iu';
            if (preg_match($pattern, $normalizedText)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize Vietnamese characters for better matching
     * 
     * @param string $text
     * @return string
     */
    private static function normalizeVietnamese($text)
    {
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');

        // Remove Vietnamese tone marks for better matching
        $search = [
            'á',
            'à',
            'ả',
            'ã',
            'ạ',
            'ă',
            'ắ',
            'ằ',
            'ẳ',
            'ẵ',
            'ặ',
            'â',
            'ấ',
            'ầ',
            'ẩ',
            'ẫ',
            'ậ',
            'é',
            'è',
            'ẻ',
            'ẽ',
            'ẹ',
            'ê',
            'ế',
            'ề',
            'ể',
            'ễ',
            'ệ',
            'í',
            'ì',
            'ỉ',
            'ĩ',
            'ị',
            'ó',
            'ò',
            'ỏ',
            'õ',
            'ọ',
            'ô',
            'ố',
            'ồ',
            'ổ',
            'ỗ',
            'ộ',
            'ơ',
            'ớ',
            'ờ',
            'ở',
            'ỡ',
            'ợ',
            'ú',
            'ù',
            'ủ',
            'ũ',
            'ụ',
            'ư',
            'ứ',
            'ừ',
            'ử',
            'ữ',
            'ự',
            'ý',
            'ỳ',
            'ỷ',
            'ỹ',
            'ỵ',
            'đ'
        ];

        $replace = [
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'i',
            'i',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'y',
            'y',
            'y',
            'y',
            'y',
            'd'
        ];

        return str_replace($search, $replace, $text);
    }

    /**
     * Thêm từ mới vào danh sách cấm
     * 
     * @param array $words
     */
    public static function addWords(array $words)
    {
        self::$profanityWords = array_merge(self::$profanityWords, $words);
        self::$profanityWords = array_unique(self::$profanityWords);
    }

    /**
     * Lấy danh sách từ cấm
     * 
     * @return array
     */
    public static function getWords()
    {
        return self::$profanityWords;
    }
}
