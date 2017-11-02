<?php

use common\models\Koatuu;
use common\models\StreetTypes;
use common\models\User;
use yii\db\Migration;

class m170327_170400_address_migration extends Migration
{
    public function up()
    {
        $this->execute("INSERT IGNORE INTO `street_types` (`id`, `name`, `short_name`) VALUES
(1,	'вулиця',	'вул.'),
(2,	'проспект',	'просп.'),
(3,	'провулок',	'пров.'),
(4,	'площа',	'пл.');");
        if (!function_exists('mb_str_replace')) {
            function mb_str_replace($search, $replace, $subject, &$count = 0)
            {
                if (!is_array($subject)) {
                    // Normalize $search and $replace so they are both arrays of the same length
                    $searches = is_array($search) ? array_values($search) : array($search);
                    $replacements = is_array($replace) ? array_values($replace) : array($replace);
                    $replacements = array_pad($replacements, count($searches), '');
                    foreach ($searches as $key => $search) {
                        $parts = mb_split(preg_quote($search), $subject);
                        $count += count($parts) - 1;
                        $subject = implode($replacements[$key], $parts);
                    }
                } else {
                    // Call mb_str_replace for each subject in array, recursively
                    foreach ($subject as $key => $value) {
                        $subject[$key] = mb_str_replace($search, $replace, $value, $count);
                    }
                }
                return $subject;
            }
        }
        $users = User::find()->all();
        $shortTypes = StreetTypes::listAll('id', 'short_name');
        $fullTypes = StreetTypes::listAll('id', 'name');
        $defaultType = StreetTypes::findOne(['short_name' => 'вул.'])->id;
        $i = 0;
        $allCount = count($users);
        $errorUser_ids = [];
        foreach ($users as $user) {
            if ($user instanceof User) {
                $type = $defaultType;
                $lNeedFindType = true;
                $address = trim(mb_strtolower($user->address, 'UTF-8'));
                $address = trim(mb_str_replace(',', ' ', $address));
                $address = trim(mb_str_replace('кв.', 'кв', $address));
                $address = trim(mb_str_replace('кв', 'кв ', $address));
                $address = trim(mb_str_replace('  ', ' ', $address));
                foreach ($shortTypes as $key => $shortType) {
                    $pos = mb_strpos($address, $shortType);
                    if ($pos !== false) {
                        $type = $key;
                        $address = trim(mb_str_replace($shortType, '', $address));
                        $lNeedFindType = false;
                        break;
                    }
                }
                if ($lNeedFindType) {
                    foreach ($fullTypes as $key => $fullType) {
                        $pos = mb_strpos($address, $fullType);
                        if ($pos !== false) {
                            $type = $key;
                            $address = trim(mb_str_replace($fullType, '', $address));
                            break;
                        }
                    }
                }
                $streetName = '';
                $build = '';
                $apartment = '';
                if (preg_match("/([а-я\`ґє´ії\s.]+)/ui", $address, $output_array)) {
                    $streetName = $output_array[0];
                    $streetNameLen = mb_strlen($streetName);
                    $trimPos = mb_strpos($address, $streetName) + $streetNameLen;
                    $streetName = mb_substr($address, 0, $trimPos);
                    $address = mb_substr($address, $trimPos);
                }
                $address = mb_split('кв', $address);

                if (count($address)) {
                    $build = trim(array_shift($address));
                    $build = mb_str_replace('-', '', $build);
                    $build = mb_str_replace('\\', '/', $build);
                    $build = mb_str_replace(' ', '', $build);
                    $build = mb_str_replace(' ', '', $build);
                }
                if (count($address)) {
                    $apartment = trim(array_shift($address));
                }
                $user->streetType = $type;
                $user->streetName = Koatuu::mb_ucfirst($streetName);
                $user->build = $build;
                $user->apartment = $apartment;
                $i++;
                if ($user->validate()) {
                    $user->save();
                    echo "$i -> $allCount \n";
                } else {
                    echo "error $user->id \n";
                    $errorUser_ids[] = $user->id;
                }
            }
        }
        var_dump(implode(', ', $errorUser_ids));
    }

    public function down()
    {
        return false;
    }
}
