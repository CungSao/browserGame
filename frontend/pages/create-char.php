<!-- chưa xog -->
<div id="createCharInfo">
    <h3>Create Character</h3>
</div>
<div class='characterSheet'>
    <div class='characterSubWrapper'>
        <div class='leftChar'>
            <div class='characterPicture'>
                There might be a picture of your character here some day
                <br><br>

            </div>
        </div>
        <div class='rightChar'>
            <div class='characterStats'>
                <form role="register" onsubmit="return validateForm()" autocomplete="off" method="post"
                    name="register-char" action="index.php?cpage=create-character&nonUI">
                    <table id="charTable" style="width:100%">
                        <tbody>
                            <tr>
                                <td>
                                    <label>Name:</label>
                                    <a><span title="">
                                            <input type="text" id="name" onchange="checkName()" name="name"
                                                pattern=".{3,12}" required class='characterFields'>
                                        </span></a>

                                    <label id="nameOk"></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="gender">Gender:</label>
                                    <select id="gender" name="gender" class='characterFields'>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="race">Race:</label>
                                    <select id="race" onchange="getRaceDesc();" name="race" class='characterFields'>
                                        <option>Human</option>
                                        <option>Elf</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <h3 id="stats"><br>stat points</h3>
                                    <input id='strength' type='number' name='strength' onchange=remainingStats()>
                                    <a title='Strength increases the damage you do in melee combat. Each weapon has a strength requirement'
                                        class='tooltipLeft'><span title=''>
                                            <span class='tooltipHover'>Strength</span>
                                        </span></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input id='dexterity' type='number' name='dexterity' onchange=remainingStats()>
                                    <a title='Dexterity greatly increases your ranged damage with bows and slightly increases your melee damage. It also gives you a boost to initiative, crit and dodge'
                                        class='tooltipLeft'><span title=''>
                                            <span class='tooltipHover'>Dexterity</span>
                                        </span></a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <h3 id="stats"><br>skill points</h3>
                                    <input id="attributes" type="number" name="one_handed" value="0" min="0" max="80"
                                        onchange="remainingSkills()">
                                    <a title="Makes you better at fighting with One-Handed weapons"
                                        class="tooltipLeft"><span title="">
                                            <span class='tooltipHover'>1H Weapons</span>
                                        </span></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input id="attributes" type="number" name="bow" value="0" min="0" max="80"
                                        onchange="remainingSkills()">
                                    <a title="Makes you better at using a Bow" class="tooltipLeft"><span title="">
                                            <span class='tooltipHover'>Bow</span>
                                        </span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <div class='characterSubWrapper characterExtras'>

        <div id="raceDesc">

        </div>
        <div id="raceTips">
            TEST
        </div>
    </div>
    <div class='characterSubWrapper characterBottom'>
        <div id="remaining" style='float:left;font-weight: bold;'>
            <div id="skills">
                0/120 skill points
            </div>
        </div>
        <br><br>
        <div class='buttonWrapper'>
            <button type="submit" class="charButton">
                Create your character!
            </button>
        </div>
    </div>
    </form>
</div>