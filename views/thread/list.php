<style>
	.item-descriptor{
		margin-top:10px;
		margin-bottom:10px;
	}
	.tab a{
		height:39px;
		line-height:39px;
		display:block;
		width:70px;
		float:left;
		text-align:center;
		text-decoration:none;
	}
	.tab .current-tab{
		border:1px solid #ddd;
		border-bottom: 1px solid #f5f5f0;
	}
	.special-left {float:left;}
	.special-right {margin-left:70px;}
	.special-img {border: 1px solid #aaa;padding:0px;margin:5px;}
	.special-p {margin:0px;}
</style>
<div class="clearfix">
	<div class="tab" style="width:auto;height:40px;border-bottom:1px solid #ddd;">
		<div style="float:right;width:auto;">
			<a class="<?= $tab_views_all?>" href="/thread/all/">すべて</a>
			<a class="<?= $tab_views_noda?>" href="/thread/noda/">野田</a>
			<a class="<?= $tab_views_katsushika?>" href="/thread/katsushika/">葛飾</a>
		</div>
	</div>
</div>
<div style="width:1px;height:30px;"></div>
<ul>
<?php foreach($threads as $thread): ?>

	<li>
		<?php if($thread->getSpecial() != NULL): ?>
			<!-- /////////////////////////////////////////////////////// -->
			<!-- ////////////        begin of special thread   ///////// -->
			<div class="clearfix">

                <?php if($thread->getSpecial() == "android"): ?>
                    <a href="/thread/<?= $thread->getId() ?>"><img class="special-left special-img" src="/images/android.png" width="50px" height="50px"></a>
                <?php elseif($thread->getSpecial() == "java"): ?>
                    <a href="/thread/<?= $thread->getId() ?>"><img class="special-left special-img" src="/images/Java_logo.png" width="50px" height="50px"></a>
                <?php elseif($thread->getSpecial() == "rss"): ?>
                	<a href="/thread/<?= $thread->getId() ?>"><img class="special-left special-img" src="/images/rss_logo.png" width="50px" height="50px"></a>
                <?php endif; ?>
                <div class="special-right ">
                    <a class="item-title anchor" href="/thread/<?= $thread-> getId() ?>">
                        <?= $thread->getTitle(); ?>
                        <?php if($thread->getPermission() == Thread::$PERMISSIONS[1]): ?>
                            <br/><span style="font-size:80%;color:black;"><i class="fa fa-globe"></i><?= $thread->getHost()->getCampus() ?>の公開スレ</span>
                        <?php endif;?>
                    </a>
                    <p class="special-p">作成者<a href="/account/<?= $thread->getHost()->getId()?>"><?= $thread->getHost()->getNickname()?></a></p>
                    <p class="item-descriptor" ><?= Utils::getReadableDateTime($thread->getUpdateTimeInMillis())."に"; ?>
                        <?php
                            $author = $thread->getLastModifiedAuthor();
                            echo "<a class='anchor' href=\"/account/".$author->getId()."\">".$author->getNickname()."</a>";
                        ?>
                    が更新</p>
                </div>

			</div>
			<!-- //////////////        end of special thread   ///////// -->
			<!-- /////////////////////////////////////////////////////// -->
		<?php else: ?>
			<a class="item-title anchor" href="/thread/<?= $thread-> getId() ?>">
				<?= $thread->getTitle(); ?>
				<?php if($thread->getPermission() == Thread::$PERMISSIONS[1]): ?>
					<br/><span style="font-size:80%;color:black;"><i class="fa fa-globe"></i><?= $thread->getHost()->getCampus() ?>の公開スレ</span>
				<?php endif;?>
			</a>
			<br/>
			<p class="item-descriptor"><?= Utils::getReadableDateTime($thread->getUpdateTimeInMillis())."に"; ?>
				<?php
					$author = $thread->getLastModifiedAuthor();
					echo "<a class='anchor' href=\"/account/".$author->getId()."\">".$author->getNickname()."</a>";
				?>
			が更新</p>
		<?php endif; ?>
		<hr />
	</li>
<?php endforeach; ?>
</ul>
<?php showPageBar($show, $page, $len ,$showPageCallback); ?>
<div style="width:1px;height:30px;"></div>