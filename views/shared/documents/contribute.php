
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>

    <div id="homepage-content" style="margin-top: 75px; margin-left: 12.5%; margin-right:12.5%; margin-bottom: 25px;">
        <form class="form-inline" id="contribute_form" method="get" action="<?php echo getFullInciteUrl(); ?>/discover">
            <div id="contribute-confirmation" style="margin: 40px; text-align: center;">
                <h2>I want to 
                    <select class="form-control" style="font-size: 60%; width: 150px;" name="task">
                        <option value="transcribe" <?php if ($this->task_type === 'transcribe') echo 'selected="selected"'; ?>>transcribe</option>
                        <option value="tag" <?php if ($this->task_type === 'tag') echo 'selected="selected"'; ?>>tag</option>
                        <option value="connect" <?php if ($this->task_type === 'connect') echo 'selected="selected"'; ?>>connect</option>
                    </select> documents in 
                    <input type="text" class="form-control" value="" style="font-size: 60%; width: 150px;" name="location" placeholder="anywhere"> from 
                    <select class="form-control" style="font-size: 60%; width: 85px;" id="time_from" name="time_from">
                        <option value="1830" selected="selected">1830</option>
                        <option value="1831">1831</option>
                        <option value="1832">1832</option>
                        <option value="1833">1833</option>
                        <option value="1834">1834</option>
                        <option value="1835">1835</option>
                        <option value="1836">1836</option>
                        <option value="1837">1837</option>
                        <option value="1838">1838</option>
                        <option value="1839">1839</option>
                        <option value="1840">1840</option>
                        <option value="1841">1841</option>
                        <option value="1842">1842</option>
                        <option value="1843">1843</option>
                        <option value="1844">1844</option>
                        <option value="1845">1845</option>
                        <option value="1846">1846</option>
                        <option value="1847">1847</option>
                        <option value="1848">1848</option>
                        <option value="1849">1849</option>
                        <option value="1850">1850</option>
                        <option value="1851">1851</option>
                        <option value="1852">1852</option>
                        <option value="1853">1853</option>
                        <option value="1854">1854</option>
                        <option value="1855">1855</option>
                        <option value="1856">1856</option>
                        <option value="1857">1857</option>
                        <option value="1858">1858</option>
                        <option value="1859">1859</option>
                        <option value="1860">1860</option>
                        <option value="1861">1861</option>
                        <option value="1862">1862</option>
                        <option value="1863">1863</option>
                        <option value="1864">1864</option>
                        <option value="1865">1865</option>
                        <option value="1866">1866</option>
                        <option value="1867">1867</option>
                        <option value="1868">1868</option>
                        <option value="1869">1869</option>
                        <option value="1870">1870</option>
                    </select> to 
                    <select class="form-control" style="font-size: 60%; width: 85px;" id="time_to" name="time_to">
                        <option value="1830">1830</option>
                        <option value="1831">1831</option>
                        <option value="1832">1832</option>
                        <option value="1833">1833</option>
                        <option value="1834">1834</option>
                        <option value="1835">1835</option>
                        <option value="1836">1836</option>
                        <option value="1837">1837</option>
                        <option value="1838">1838</option>
                        <option value="1839">1839</option>
                        <option value="1840">1840</option>
                        <option value="1841">1841</option>
                        <option value="1842">1842</option>
                        <option value="1843">1843</option>
                        <option value="1844">1844</option>
                        <option value="1845">1845</option>
                        <option value="1846">1846</option>
                        <option value="1847">1847</option>
                        <option value="1848">1848</option>
                        <option value="1849">1849</option>
                        <option value="1850">1850</option>
                        <option value="1851">1851</option>
                        <option value="1852">1852</option>
                        <option value="1853">1853</option>
                        <option value="1854">1854</option>
                        <option value="1855">1855</option>
                        <option value="1856">1856</option>
                        <option value="1857">1857</option>
                        <option value="1858">1858</option>
                        <option value="1859">1859</option>
                        <option value="1860">1860</option>
                        <option value="1861">1861</option>
                        <option value="1862">1862</option>
                        <option value="1863">1863</option>
                        <option value="1864">1864</option>
                        <option value="1865">1865</option>
                        <option value="1866">1866</option>
                        <option value="1867">1867</option>
                        <option value="1868">1868</option>
                        <option value="1869">1869</option>
                        <option value="1870" selected="selected">1870</option>
                    </select>.
                </h2>
            </div>   <!-- contribute-confirmation -->
            <div style="text-align: center;">
                <button type="button" id="contribute_button" style="margin: 40px;" class="btn btn-danger">TRY IT NOW</button>
            </div>
        </form>
    </div> <!-- homepage-content -->

    <script>
       $(document).ready( function () {
            $('#contribute_button').on('click', function (e) {
                if ($('#time_from').val() > $('#time_to').val()) {
                    notif({
                        type: "warning",
                        msg: "<b>Warning:</b> \"from\" time cannot be later than \"to\" time!",
                        position: "right"
                    });
                } else {
                    $('#contribute_form').submit();
                }
            });

        }); 
    </script>


</html>
