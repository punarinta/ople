<head>
<title>Verb conjugator</title>
<style>
    table {border-collapse: collapse}
    td, th {border: 1px solid #888; padding:3px 7px}
    th {background: #f0f0f0}
    td:nth-child(n+2) {text-align: center}
    tr.skip td {border: 0}
    input {font-size:24px}
</style>
</head>

<?php

$verbs = require_once('../../lib/verb.php');
$verb_tokens = array_keys($verbs);

$inf = isset ($_GET['verb']) ? $_GET['verb'] : ($verb_tokens[mt_rand(0, count($verb_tokens) - 1)]);

if (substr($inf, -1) != 'j')
{
    echo 'Not an infinitive form';
    exit;
}

function conj_pn($stem, $person, $number)
{
    if ($number == 3) return $stem . 'ń';

    if ($person == 1) $stem .= 'm';
    else if ($person == 2) $stem .= 't';
    else if ($person == 3) $stem .= 'h';

    if ($number == 2) return $stem . 'i';

    return $stem;
}

function pers_pron($person, $number)
{
    $stem = '';

    if ($person == 1) $stem = 'm';
    else if ($person == 2) $stem = 't';
    else if ($person == 3) $stem = 'h';
    if ($number == 1) $stem .= 'a';
    else if ($number == 2) $stem .= 'i';
    else if ($number == 3) $stem .= 'ńi';

    return $stem;
}

function vowel_stem($inf)
{
    return rtrim($inf, 'j');
}

// is always longer than vowel stem
function root_stem($stem, $termintaor)
{
    // TODO
    return rtrim($stem, 'j') . $termintaor;
}

function is_vowel($symbol)
{
    return in_array($symbol, ['a', 'o', 'i', 'y', 'u', 'e', 'á', 'ó', 'ú']);
}

function is_opened($stem)
{
    $l = strlen($stem);
    for ($i = 0; $i < $l; $i++)
    {
        if ($i > 1 && !is_vowel($stem[$i]) && !is_vowel($stem[$i - 1]))
        {
            return false;
        }
    }
    return true;
}

?>

<form action="" method="get">
    <input type="text" name="verb" autofocus value="<?=$inf?>"/>
</form>

<h3>Transtalion</h3>
<?
    if (isset ($verbs[$inf])) echo implode(', ', $verbs[$inf]);
    else echo '[not found in the vocabulary]';
?>
<br/><br/>

<h3>Indicative</h3>
<table>
    <thead>
        <tr>
            <th>Factor</th><th>Period</th>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<th>' . pers_pron($p, $n) . '</th>'; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="3">Incomplete</td><td>Present</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn(vowel_stem($inf), $p, $n) . '</td>'; ?>
        </tr>
        <tr>
            <td>Past</td>
            <?
            $stem = vowel_stem($inf);

            if (substr($stem, -2) == 'uo') $stem = substr($stem, 0, -2) . 'vu';
            else if (substr($stem, -1) == 'o') $stem = substr($stem, 0, -1) . 'u';
            else if (substr($stem, -2) == 'ae') $stem = substr($stem, 0, -2) . 'au';

            // if the previous syllable is closed, the vowel is exchanged with 'u'
            else if (!is_opened(substr($stem, 0, -1))) $stem = substr($stem, 0, -1) . 'u';
            else if (is_vowel(substr($stem, -1)) && is_vowel(substr($stem, -2, 1))) $stem = substr($stem, 0, -1) . 'u';
            else $stem .= 'u';

            for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn($stem, $p, $n) . '</td>'; ?>
        </tr>
        <tr>
            <td>Future</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('tope', $p, $n) . ' ' . $inf . '</td>'; ?>
        </tr>
        <tr>
            <td rowspan="3">Ongoing</td><td>Present</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('o', $p, $n) . ' ke ' . vowel_stem($inf) . 'tuh' . '</td>'; ?>
        </tr>
        <tr>
            <td>Past</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('u', $p, $n) . ' ke ' . vowel_stem($inf) . 'tuh' . '</td>'; ?>
        </tr>
        <tr>
            <td>Future</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('tope', $p, $n) . ' oj ke ' . vowel_stem($inf) . 'tuh' . '</td>'; ?>
        </tr>
        <tr>
            <td rowspan="3">Complete</td><td>Present</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('o', $p, $n) . ' ke ' .  vowel_stem($inf) . 's' . '</td>'; ?>
        </tr>
        <tr>
            <td>Past</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('u', $p, $n) . ' ke ' . vowel_stem($inf) . 's' . '</td>'; ?>
        </tr>
        <tr>
            <td>Future</td>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<td>' . conj_pn('tope', $p, $n) . ' oj ke ' . vowel_stem($inf) . 's' . '</td>'; ?>
        </tr>
     </tbody>
</table>
<br/>

<h3>Imperative</h3>
<table>
    <thead>
        <tr>
            <? for ($n = 1; $n <= 3; $n++) for ($p = 1; $p <= 3; $p++) echo '<th>' . pers_pron($p, $n) . '</th>'; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>—</td><td><? echo vowel_stem($inf); ?></td><td>—</td><td><? echo 'mi' . vowel_stem($inf); ?></td><td><? echo vowel_stem($inf) . 'ħi'; ?></td><td colspan="4">—</td>
        </tr>
    </tbody>
</table>
<br/>

<h3>Casuative</h3>
<?
if (substr($inf, -3) == 'soj') echo '—';
else echo vowel_stem($inf) . 'soj';
?>
<br/><br/>

<h3>Nominative forms</h3>
<table>
    <thead>
        <tr>
            <th>Result</th><th>Process</th><th>Agent</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><? echo vowel_stem($inf) . 's'; ?></td>
            <td><? echo vowel_stem($inf) . 'tu'; ?></td>
            <td><? echo $inf . 'or'; ?></td>
        </tr>
    </tbody>
</table>
<br/>
