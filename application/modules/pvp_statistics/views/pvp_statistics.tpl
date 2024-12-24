{if $realms_count > 0}
	<ul class="nav nav-pills mb-3" id="pvp-tab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="pvp-kills-tab" data-bs-toggle="pill" data-bs-target="#pvp-kills" type="button" role="tab" aria-controls="pvp-kills" aria-selected="true">Top Kills</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="pvp-2v2-tab" data-bs-toggle="pill" data-bs-target="#pvp-2v2" type="button" role="tab" aria-controls="pvp-2v2" aria-selected="false">2v2</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="pvp-3v3-tab" data-bs-toggle="pill" data-bs-target="#pvp-3v3" type="button" role="tab" aria-controls="pvp-3v3" aria-selected="false">3v3</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="pvp-5v5-tab" data-bs-toggle="pill" data-bs-target="#pvp-5v5" type="button" role="tab" aria-controls="pvp-5v5" aria-selected="false">Soloq</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="rbgs-tab" data-bs-toggle="pill" data-bs-target="#rbgs" type="button" role="tab" aria-controls="rbgs-tab" aria-selected="false">RBGs</button>
		</li>
		<li class="nav-item" role="presentation">
			<select style="width: 200px;" id="realm-changer" onchange="return RealmChange();">
				{foreach from=$realms item=realm key=realmId}
					<option value="{$realmId}" {if $selected_realm == $realmId}selected="selected"{/if}>{$realm.name}</option>
				{/foreach}
			</select>
		</li>
	</ul>
	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="pvp-kills" role="tabpanel" aria-labelledby="pvp-kills-tab">
			<table class="nice_table">
				{if $TopHK}
					<tr>
						<td align="center"></td>
						<td align="center">{lang("rank", "pvp_statistics")}</td>
						<td align="center">{lang("character", "pvp_statistics")}</td>
						<td align="center">{lang("level", "pvp_statistics")}</td>
						<td align="center">{lang("kills", "pvp_statistics")}</td>
						<td align="center">{lang("race", "pvp_statistics")}</td>
						<td align="center">{lang("class", "pvp_statistics")}</td>
					</tr>
					{foreach from=$TopHK item=character}
					<tr>
						<td align="center">
							{if $character.rank == '1st'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/1.ico"/>
							{/if}
							{if $character.rank == '2nd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/2.ico"/>
							{/if}
							{if $character.rank == '3rd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/3.ico"/>
							{/if}
						</td>
						<td align="center">
							{$character.rank}
						</td>

						<td align="center">
							<a href="{$url}character/{$selected_realm}/{$character.guid}">{$character.name}</a>
						</td>

						<td align="center">{$character.level}</td>

						<td align="center">{$character.kills}</td>

						<td align="center">
							<img src="{$url}application/images/stats/{$character.race}-{$character.gender}.gif" width="20" height="20">
						</td>

						<td align="center">
							<img src="{$url}application/images/stats/{$character.class}.gif" width="20" height="20">
						</td>

					</tr>
					{/foreach}
				{else}
					<tr>
						<td colspan="5"><center>{lang("no_kills", "pvp_statistics")}</center></td>
					</tr>
				{/if}
			</table>
		</div>

		<div class="tab-pane fade" id="pvp-2v2" role="tabpanel" aria-labelledby="pvp-2v2-tab">
			{if $Teams2}
			<table class="nice_table">
				<tr>
					<td align="center"></td>
					<td align="center">{lang("rank", "pvp_statistics")}</td>
					<td align="center">{lang("character", "pvp_statistics")}</td>
					<td align="center">{lang("rating", "pvp_statistics")}</td>
					<td align="center">{lang("season_games", "pvp_statistics")}</td>
					<td align="center">{lang("season_wins", "pvp_statistics")}</td>
					<td align="center">{lang("race", "pvp_statistics")}</td>
					<td align="center">{lang("class", "pvp_statistics")}</td>
					<td align="center">{lang("faction", "pvp_statistics")}</td>
					<td align="center">{lang("category", "pvp_statistics")}</td>
				</tr>
				{foreach from=$Teams2 item=character}
					<tr>
						<td align="center">
							{if $character.ord_rank == '1st'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/1.ico"/>
							{/if}
							{if $character.ord_rank == '2nd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/2.ico"/>
							{/if}
							{if $character.ord_rank == '3rd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/3.ico"/>
							{/if}
						</td>					
						<td align="center">
							{$character.ord_rank}
						</td>
						<td align="center">
							<a href="{$url}character/{$selected_realm}/{$character.guid}"
								data-tip="<font style='font-weight: bold;'>{$character.character_name}</font>">
								{$character.character_name}
							</a>
						</td>
						<td align="center">
							{$character.rating}
						</td>
						<td align="center">
							{$character.season_games}
						</td>
						<td align="center">
							{$character.season_wins}
						</td>
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_race}-{$character.character_gender}.gif" width="20" height="20" />
						</td>
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_class}.gif" width="20" height="20" />
						</td>
						<td align="center">
							{if !($character.nomber_faction == '')}
								<img src="{$url}application/images/factions/{$character.number_faction}.png" width="20" height="20"
									data-tip="<font style='font-weight: bold;'>{$character.nomber_faction}</font>" alt= {$character.nomber_faction} />
							{/if}
						</td>
						<td align="center">
							{if !($character.category == '')}
								<img width="20px" height="20px" src='{$url}application/images/categorias/{$character.category}.png'
									data-tip="<font style='font-weight: bold;'>{$character.category}</font>" alt= {$character.category} />
							{/if}
						</td>
					</tr>
				{/foreach}
			{else}
				{lang("no_2v2", "pvp_statistics")}
			{/if}
			</table>
		</div>

		<div class="tab-pane fade" id="pvp-3v3" role="tabpanel" aria-labelledby="pvp-3v3-tab">
			{if $Teams3}
			<table class="nice_table">
				<tr>
					<td align="center"></td>					
					<td align="center">{lang("rank", "pvp_statistics")}</td>
					<td align="center">{lang("character", "pvp_statistics")}</td>
					<td align="center">{lang("rating", "pvp_statistics")}</td>
					<td align="center">{lang("season_games", "pvp_statistics")}</td>
					<td align="center">{lang("season_wins", "pvp_statistics")}</td>
					<td align="center">{lang("race", "pvp_statistics")}</td>
					<td align="center">{lang("class", "pvp_statistics")}</td>
					<td align="center">{lang("faction", "pvp_statistics")}</td>
					<td align="center">{lang("category", "pvp_statistics")}</td>
				</tr>
				{foreach from=$Teams3 item=character}
					<tr>
						<td align="center">
							{if $character.ord_rank == '1st'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/1.ico"/>
							{/if}
							{if $character.ord_rank == '2nd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/2.ico"/>
							{/if}
							{if $character.ord_rank == '3rd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/3.ico"/>
							{/if}
						</td>					
						<td align="center">
							{$character.ord_rank}
						</td>
						<td align="center">
							<a href="{$url}character/{$selected_realm}/{$character.guid}"
								data-tip="<font style='font-weight: bold;'>{$character.character_name}</font>">
								{$character.character_name}
							</a>
						</td>
						<td align="center">
							{$character.rating}
						</td>
						<td align="center">
							{$character.season_games}
						</td>
						<td align="center">
							{$character.season_wins}
						</td>
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_race}-{$character.character_gender}.gif" width="20" height="20" />
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_class}.gif" width="20" height="20" />
						</td>
						</td>
						<td align="center">
							{if !($character.nomber_faction == '')}
								<img src="{$url}application/images/factions/{$character.number_faction}.png" width="20" height="20"
									data-tip="<font style='font-weight: bold;'>{$character.nomber_faction}</font>" alt= {$character.nomber_faction} />
							{/if}
						</td>
						<td align="center">
							{if !($character.category == '')}
								<img width="20px" height="20px" src='{$url}application/images/categorias/{$character.category}.png'
									data-tip="<font style='font-weight: bold;'>{$character.category}</font>" alt= {$character.category} />
							{/if}
						</td>
					</tr>
				{/foreach}
			{else}
				{lang("no_3v3", "pvp_statistics")}
			{/if}
			</table>
		</div>

		<div class="tab-pane fade" id="pvp-5v5" role="tabpanel" aria-labelledby="pvp-5v5-tab">
			{if $Teams5}
			<table class="nice_table">
				<tr>
					<td align="center"></td>					
					<td align="center">{lang("rank", "pvp_statistics")}</td>
					<td align="center">{lang("character", "pvp_statistics")}</td>
					<td align="center">{lang("rating", "pvp_statistics")}</td>
					<td align="center">{lang("season_games", "pvp_statistics")}</td>
					<td align="center">{lang("season_wins", "pvp_statistics")}</td>
					<td align="center">{lang("race", "pvp_statistics")}</td>
					<td align="center">{lang("class", "pvp_statistics")}</td>
					<td align="center">{lang("faction", "pvp_statistics")}</td>
					<td align="center">{lang("category", "pvp_statistics")}</td>
				</tr>
				{foreach from=$Teams5 item=character}
					<tr>
						<td align="center">
							{if $character.ord_rank == '1st'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/1.ico"/>
							{/if}
							{if $character.ord_rank == '2nd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/2.ico"/>
							{/if}
							{if $character.ord_rank == '3rd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/3.ico"/>
							{/if}
						</td>					
						<td align="center">
							{$character.ord_rank}
						</td>
						<td align="center">
							<a href="{$url}character/{$selected_realm}/{$character.guid}"
								data-tip="<font style='font-weight: bold;'>{$character.character_name}</font>">
								{$character.character_name}
							</a>
						</td>
						<td align="center">
							{$character.rating}
						</td>
						<td align="center">
							{$character.season_games}
						</td>
						<td align="center">
							{$character.season_wins}
						</td>
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_race}-{$character.character_gender}.gif" width="20" height="20">
						</td>
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_class}.gif" width="20" height="20">
						</td>
						<td align="center">
							{if !($character.nomber_faction == '')}
								<img src="{$url}application/images/factions/{$character.number_faction}.png" width="20" height="20"
									data-tip="<font style='font-weight: bold;'>{$character.nomber_faction}</font>" alt= {$character.nomber_faction} />
							{/if}
						</td>
						<td align="center">
							{if !($character.category == '')}
								<img width="20px" height="20px" src='{$url}application/images/categorias/{$character.category}.png'
									data-tip="<font style='font-weight: bold;'>{$character.category}</font>" alt= {$character.category} />
							{/if}
						</td>
					</tr>
				{/foreach}
			{else}
				{lang("no_soloq", "pvp_statistics")}
			{/if}
			</table>
		
		</div>

		<div class="tab-pane fade" id="rbgs" role="tabpanel" aria-labelledby="rbgs-tab">
			{if $RBGs}
			<table class="nice_table">
				<tr>
					<td align="center"></td>					
					<td align="center">{lang("rank", "pvp_statistics")}</td>
					<td align="center">{lang("character", "pvp_statistics")}</td>
					<td align="center">{lang("rating", "pvp_statistics")}</td>
					<td align="center">{lang("season_games", "pvp_statistics")}</td>
					<td align="center">{lang("season_wins", "pvp_statistics")}</td>
					<td align="center">{lang("race", "pvp_statistics")}</td>
					<td align="center">{lang("class", "pvp_statistics")}</td>
					<td align="center">{lang("faction", "pvp_statistics")}</td>
					<td align="center">{lang("category", "pvp_statistics")}</td>
				</tr>
				{foreach from=$RBGs item=character}
					<tr>
						<td align="center">
							{if $character.ord_rank == '1st'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/1.ico"/>
							{/if}
							{if $character.ord_rank == '2nd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/2.ico"/>
							{/if}
							{if $character.ord_rank == '3rd'}
								<img width="16px" height="16px" src="{$url}application/modules/sidebox_top/images/3.ico"/>
							{/if}
						</td>					
						<td align="center">
							{$character.ord_rank}
						</td>
						<td align="center">
							<a href="{$url}character/{$selected_realm}/{$character.guid}"
								data-tip="<font style='font-weight: bold;'>{$character.character_name}</font>">
								{$character.character_name}
							</a>
						</td>
						<td align="center">
							{$character.rating}
						</td>
						<td align="center">
							{$character.season_games}
						</td>
						<td align="center">
							{$character.season_wins}
						</td>
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_race}-{$character.character_gender}.gif" width="20" height="20">
						<td align="center">
							<img src="{$url}application/images/stats/{$character.character_class}.gif" width="20" height="20">
						</td>
						</td>
						<td align="center">
							{if !($character.nomber_faction == '')}
								<img src="{$url}application/images/factions/{$character.number_faction}.png" width="20" height="20"
									data-tip="<font style='font-weight: bold;'>{$character.nomber_faction}</font>" alt= {$character.nomber_faction} />
							{/if}
						</td>
						<td align="center">
							{if !($character.category == '')}
								<img width="20px" height="20px" src='{$url}application/images/categorias/{$character.category}.png'
									data-tip="<font style='font-weight: bold;'>{$character.category}</font>" alt= {$character.category} />
							{/if}
						</td>
					</tr>
				{/foreach}
			{else}
				{lang("no_rgbs", "pvp_statistics")}
			{/if}
			</table>
		</div>
	</div>
{/if}