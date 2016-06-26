<?php
namespace User\Service;
/**
 * 用户会员身份,包含更新用户信息功能 和 用户升级功能
 * 
 * @codeCoverageIgnore
 * 
 * @author chloroplast
 * @version 1.0:20160227
 */

interface MemberServiceInterface {

	/**
	 * 更新用户信息
	 */
	function updateProfile(int $id,int $avatarId, string $realName,string $provinceId,string $cityId, string $districtId,string $email,string $qq);

	/**
	 * 更新用户密码
	 * @param int $id 用户id
	 * @param string $password 用户密码
	 */
	function updatePassword(int $id,string $password);
}